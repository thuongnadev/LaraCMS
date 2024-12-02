<?php

namespace Modules\Theme\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Modules\Form\Entities\FormFieldValue;
use Modules\Form\Entities\FormSubmission;
use Modules\Theme\Traits\HandleColorTrait;

class FormContact extends Component
{
    use HandleColorTrait;

    public $primaryColor;
    public $form;
    public $formFields = [];
    public $formData = [];
    public $primaryColorRgb;
    public $successMessage;
    public $errorMessage;
    public $loading = false;
    public $title = '';

    public function mount($form)
    {
        $this->primaryColor = $this->getFilamentPrimaryColor();
        if ($this->isHexColor($this->primaryColor)) {
            $this->primaryColorRgb = $this->hexToRgb($this->primaryColor);
        } else {
            $this->primaryColorRgb = $this->primaryColor;
        }
        $this->form = $form;
        $this->formFields = $form->formFields;
        $this->successMessage = $form->notification->success_message ?? 'Xin cảm ơn, thông tin của bạn đã được gửi thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất!';
        $this->errorMessage = $form->notification->error_message ?? 'Đã có lỗi xảy ra khi gửi biểu mẫu. Vui lòng thử lại sau.';

        foreach ($this->formFields as $field) {
            $this->formData[$field->name] = '';
        }

        $this->resetForm();

        $this->formFields = $form->formFields;
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

        $this->formData['form_info'] = $this->form->name . ' - ' . $this->title;

        $rules = $this->rules();

        $messages = $this->messages();

        $this->validate($rules, $messages);

        try {
            $submission = FormSubmission::create([
                'form_id' => $this->form->id,
                'is_viewed' => false,
            ]);

            foreach ($this->formFields as $field) {
                FormFieldValue::create([
                    'form_submission_id' => $submission->id,
                    'form_field_id' => $field->id,
                    'value' => $this->formData[$field->name],
                ]);
            }

            FormFieldValue::create([
                'form_submission_id' => $submission->id,
                'form_field_id' => null,
                'value' => $this->formData['form_info'],
            ]);

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

    #[On('setNameForm')]
    public function setNameForm($name)
    {
        $this->title = $name;
    }

    #[On('resetNameForm')]
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
        return view('theme::livewire.form-contact');
    }
}
