<?php

namespace Modules\Theme\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Modules\Theme\Traits\HandleCalculateTrait;
use Modules\Theme\Traits\HandleColorTrait;
use Illuminate\Support\Facades\Request;
use Modules\Form\Entities\Form;

class DomainLookupDetail extends Component
{
    use HandleColorTrait, HandleCalculateTrait;

    const API_ENDPOINT = 'https://tracking-domain.goldenbeeltd.vn/';

    public string $primaryColor;
    public string $domain = '';
    public ?string $tld;
    public array $domainData = [];
    public bool $isLoading = true;
    public string $selectedDomain = '';
    public array $tldList = [];
    public array $tldGroups = [];
    public array $selectedTlds = [];
    public $formId;
    public $apiEndpoint = '';

    protected $listeners = [
        'updateDomainData' => 'updateDomainData',
        'updateSelectedTlds' => 'updateSelectedTlds',
        'setNameForm' => 'setNameForm',
        'resetForm' => 'resetForm',
        'addTld' => 'addTld',
        'refreshDomainData' => 'refreshDomainData',
    ];

    public function mount(): void
    {
        $this->primaryColor = $this->getFilamentPrimaryColor();
        $cachedConfigs = Cache::get('domain_lookup_configs');
        $this->formId = $cachedConfigs['form_consulting_domain'];
        $this->tldList = $cachedConfigs['domains'] ?? [];
        $this->tldGroups = $this->getTldGroups();
        $this->domain = Request::query('domain', '');
        $this->selectedTlds = $this->tldList ?: array_keys($this->tldGroups['popular']);
        $this->apiEndpoint = self::API_ENDPOINT;
    }

    public function updateDomainData($domainData, $searchTld = null)
    {
        $this->domainData = array_values(array_filter(array_map(function ($item) {
            if (isset($item['domain'])) {
                $tld = substr($item['domain'], strpos($item['domain'], '.'));
                if ($this->isSupportedTld($tld)) {
                    if (isset($item['data']) && is_array($item['data'])) {
                        $formattedData = $this->formatDomainData($item['data']);
                        return [
                            'domain' => $item['domain'],
                            'data' => $formattedData,
                            'is_registered' => $formattedData['is_registered'],
                            'error' => $item['error'] ?? null
                        ];
                    } else {
                        return [
                            'domain' => $item['domain'],
                            'data' => null,
                            'is_registered' => false,
                            'error' => $item['error'] ?? 'Không thể lấy thông tin domain'
                        ];
                    }
                }
            }
            return null;
        }, $domainData), function ($item) {
            return $item !== null;
        }));

        if ($searchTld && $this->isSupportedTld($searchTld) && !in_array($searchTld, $this->selectedTlds)) {
            $this->selectedTlds[] = $searchTld;
            $this->dispatch('tldListUpdated');
        }

        $this->isLoading = false;
    }
    public function addTld($tld)
    {
        if ($this->isSupportedTld($tld) && !in_array($tld, $this->selectedTlds)) {
            $this->selectedTlds[] = $tld;
            $this->dispatch('tldListUpdated');
        }
    }

    private function isSupportedTld($tld)
    {
        foreach ($this->tldGroups as $group => $tlds) {
            if (isset($tlds[$tld])) {
                return true;
            }
        }
        return false;
    }

    private function formatDomainData($data)
    {
        if (!is_array($data)) {
            return null;
        }

        return [
            'domain_name' => $data['domain_name'] ?? 'N/A',
            'registration_date' => $data['registration_date'] && $data['registration_date'] != "(yyyy-mm-dd)" ?  $this->formatDate($data['registration_date']) : 'Không có thông tin.',
            'expiration_date' => $data['expiration_date'] && $data['expiration_date'] != "(yyyy-mm-dd)" ? $this->formatDate($data['expiration_date']) : 'Không có thông tin.',
            'registrar' => $data['registrar'] ?? 'N/A',
            'status' => $this->formatStatus($data['status'] ?? ''),
            'name_servers' => is_array($data['name_servers']) ? $data['name_servers'] : [$data['name_servers'] ?? 'Không có thông tin'],
            'is_registered' => $data['domain_name'] !== 'Không có thông tin',
        ];
    }

    private function formatDate($dateString)
    {
        if (empty($dateString)) return 'N/A';

        $dateString = preg_replace('/\s*\(.*\)$/', '', $dateString);

        try {
            $date = new \DateTime($dateString);
            return $date->format('d/m/Y H:i:s');
        } catch (\Exception $e) {
            return $dateString;
        }
    }

    private function formatStatus($status)
    {
        $statuses = explode(' ', $status);
        return ucfirst($statuses[0]);
    }

    public function updateSelectedTlds($selectedTlds)
    {
        $this->selectedTlds = $selectedTlds;
    }

    public function fetchDomainData()
    {
        $this->isLoading = true;
        $this->dispatch('updateUrl', ['domain' => $this->domain]);
    }

    public function showError($message)
    {
        $this->addError('domainCheck', $message);
    }

    public function fetchForm()
    {
        $formId = $this->formId ?? null;

        if ($formId !== null) {
            if (is_string($formId) && ctype_digit($formId)) {
                $formId = (int) $formId;
            }

            return Form::with([
                'formFields',
                'formFields.fieldValues',
                'submissions',
                'emailSetting',
                'notification'
            ])->find($formId);
        }

        return null;
    }

    private function getTldGroups(): array
    {
        return [
            'popular' => [
                '.vn' => 'Tên miền Việt Nam .vn',
                '.com.vn' => 'Tên miền Việt Nam .com.vn',
                '.net.vn' => 'Tên miền Việt Nam .net.vn',
                '.com' => 'Tên miền quốc tế .com',
                '.net' => 'Tên miền quốc tế .net',
                '.info' => 'Tên miền quốc tế .info',
                '.org' => 'Tên miền quốc tế .org',
                '.asia' => 'Tên miền quốc tế .asia',
            ],
            'vietnam' => [
                '.edu.vn' => 'Tên miền giáo dục .edu.vn',
                '.gov.vn' => 'Tên miền chính phủ .gov.vn',
                '.biz.vn' => 'Tên miền doanh nghiệp .biz.vn',
                '.org.vn' => 'Tên miền tổ chức .org.vn',
                '.name.vn' => 'Tên miền cá nhân .name.vn',
                '.info.vn' => 'Tên miền thông tin .info.vn',
                '.pro.vn' => 'Tên miền chuyên nghiệp .pro.vn',
                '.health.vn' => 'Tên miền y tế .health.vn',
            ],
            'international' => [
                '.biz' => 'Tên miền doanh nghiệp .biz',
                '.name' => 'Tên miền cá nhân .name',
                '.cc' => 'Tên miền quốc tế .cc',
                '.co' => 'Tên miền quốc tế .co',
                '.eu' => 'Tên miền châu Âu .eu',
                '.pro' => 'Tên miền chuyên nghiệp .pro',
                '.bz' => 'Tên miền quốc tế .bz',
                '.tv' => 'Tên miền truyền hình .tv',
                '.me' => 'Tên miền cá nhân .me',
                '.ws' => 'Tên miền quốc tế .ws',
                '.in' => 'Tên miền Ấn Độ .in',
                '.us' => 'Tên miền Hoa Kỳ .us',
                '.co.uk' => 'Tên miền Vương Quốc Anh .co.uk',
                '.mobi' => 'Tên miền di động .mobi',
                '.tel' => 'Tên miền liên lạc .tel',
            ],
        ];
    }

    public function render()
    {
        return view('theme::livewire.domain-lookup-detail', [
            'form' => $this->fetchForm(),
        ]);
    }
}
