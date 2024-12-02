<?php

namespace Modules\Theme\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Form\Entities\FormFieldValue;
use Modules\Form\Entities\FormSubmission;
use Modules\Form\Entities\EmailSetting;
use Modules\Theme\Traits\HandleColorTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Form extends Component
{
    use HandleColorTrait, WithFileUploads;

    public $primaryColor;
    public $form;
    public $formFields = [];
    public $formData = [];
    public $successMessage;
    public $errorMessage;
    public $loading = false;
    public $title = '';

    public function mount($form)
    {
        $this->primaryColor = $this->getFilamentPrimaryColor();
        $this->form = $form;
        $this->successMessage = $form->notification->success_message ?? 'Xin cảm ơn, thông tin của bạn đã được gửi thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất!';
        $this->errorMessage = $form->notification->error_message ?? 'Đã có lỗi xảy ra khi gửi biểu mẫu. Vui lòng thử lại sau.';

        if (isset($form->formFields)) {
            $this->formFields = $form->formFields;

            foreach ($this->formFields as $field) {
                $this->formData[$field->name] = '';
            }

            $this->resetForm();

            $this->formFields = $form->formFields;
        }
    }

    public function rules()
    {
        $rules = [];

        foreach ($this->formFields as $field) {
            $fieldRules = [];

            if ($field->is_required) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            if ($field->type === 'email') {
                $fieldRules[] = 'email';
            }

            if ($field->type === 'tel') {
                $fieldRules[] = 'regex:/^0\d{9}$/';
            }

            if ($field->min_length) {
                $fieldRules[] = 'min:' . $field->min_length;
            }

            if ($field->max_length) {
                $fieldRules[] = 'max:' . $field->max_length;
            }

            $rules['formData.' . $field->name] = $fieldRules;
        }

        return $rules;
    }

    protected function messages()
    {
        $messages = [];

        foreach ($this->formFields as $field) {
            $fieldName = $field->label ?? $field->name;

            if ($field->is_required) {
                $messages['formData.' . $field->name . '.required'] = $field->required_message ?? "Trường $fieldName là bắt buộc.";
            }

            if ($field->type === 'email') {
                $messages['formData.' . $field->name . '.email'] = $field->email_message ?? "Trường $fieldName phải là một địa chỉ email hợp lệ.";
            }

            if ($field->type === 'tel') {
                $messages['formData.' . $field->name . '.regex'] = $field->tel_message ?? "Trường $fieldName phải là số điện thoại hợp lệ (10 chữ số, bắt đầu bằng số 0).";
            }

            if ($field->min_length) {
                $messages['formData.' . $field->name . '.min'] = $field->min_length_message ?? "Trường $fieldName phải có ít nhất {$field->min_length} ký tự.";
            }

            if ($field->max_length) {
                $messages['formData.' . $field->name . '.max'] = $field->max_length_message ?? "Trường $fieldName không được vượt quá {$field->max_length} ký tự.";
            }
        }

        return $messages;
    }

    public function submitForm()
    {
        $this->loading = true;

        if(isset($this->form->name)) {
            $this->formData['form_info'] = $this->form->name . ' - ' . $this->title;
        } else {
            return;
        }

        $rules = $this->rules();
        $messages = $this->messages();

        $this->validate($rules, $messages);

        try {
            $submission = FormSubmission::create([
                'form_id' => $this->form->id,
                'is_viewed' => false,
            ]);

            foreach ($this->formFields as $field) {
                $value = $this->formData[$field->name];

                if ($field->type === 'file' && $value) {
                    $path = $value->store('uploads', 'public');
                    $value = $path;
                }

                FormFieldValue::create([
                    'form_submission_id' => $submission->id,
                    'form_field_id' => $field->id,
                    'value' => $value,
                ]);
            }

            FormFieldValue::create([
                'form_submission_id' => $submission->id,
                'form_field_id' => null,
                'value' => $this->formData['form_info'],
            ]);
            $this->sendEmail($submission);

            $this->formData = array_fill_keys(array_keys($this->formData), '');

            $this->dispatch('show-message', [
                'type' => 'success',
                'message' => $this->successMessage
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-message', [
                'type' => 'error',
                'message' => $this->errorMessage
            ]);
        }

        $this->loading = false;
    }

    protected function sendEmail($submission)
    {
        $emailSetting = EmailSetting::where('form_id', $this->form->id)->first();

        if ($emailSetting) {
            $toEmail = $emailSetting->to_email;
            $fromEmail = $emailSetting->from_email;
            $subject = $emailSetting->subject;
            $messageBody = $emailSetting->message_body;

            foreach ($this->formFields as $field) {
                $key = $field->name;
                $value = $this->formData[$key] ?? '';
                if (is_array($value)) {
                    $value = implode(', ', $value);
                }
                $messageBody = str_replace("{{ $key }}", $value, $messageBody);
            }

            $appUrl = config('app.url');
            $messageBody .= "\n\nĐược gửi từ website: [{$appUrl}]";

            $subject = "[Form: {$this->form->name}] " . $subject;
            $messageBody = "Form Name: {$this->form->name}\n\n" . $messageBody;

            $mailerType = $emailSetting->mailer_type ?? config('mail.default');

            Mail::mailer($mailerType)->send([], [], function ($message) use ($toEmail, $fromEmail, $subject, $messageBody) {
                $message->to($toEmail)
                    ->from($fromEmail)
                    ->subject($subject)
                    ->html(nl2br($messageBody));

                foreach ($this->formFields as $field) {
                    if ($field->type === 'file' && isset($this->formData[$field->name])) {
                        $filePath = Storage::disk('public')->path($this->formData[$field->name]);
                        $fileName = basename($filePath);
                        $message->attach($filePath, ['as' => $fileName]);
                    }
                }
            });
        }
    }

    #[On('setNameForm')]
    public function setNameForm($name)
    {
        $this->title = $name;
    }

    #[On('resetForm')]
    public function resetForm()
    {
        foreach ($this->formFields as $field) {
            $this->formData[$field->name] = '';
        }
        $this->resetValidation();
        $this->loading = false;
    }

    public function render()
    {
        return view('theme::livewire.form');
    }
}
