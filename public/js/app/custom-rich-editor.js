document.addEventListener('alpine:init', () => {
    Alpine.data('initializeRichEditor', () => ({
        init() {
            const editor = this.$el.querySelector('trix-editor');
            const toolbarElement = editor.toolbarElement;

            const addSectionIdButton = document.createElement('button');
            addSectionIdButton.type = 'button';
            addSectionIdButton.classList.add('trix-button');
            addSectionIdButton.classList.add('trix-button--icon-add-id');
            addSectionIdButton.title = 'Thêm ID cho phần';
            addSectionIdButton.textContent = '#';

            const addSectionIdListener = () => {
                const selectedRange = editor.selectionManager.getSelectedRange();
                if (selectedRange[0] === selectedRange[1]) {
                    alert('Vui lòng chọn đoạn văn bản để thêm ID');
                    return;
                }

                const id = prompt('Nhập ID cho phần này:');
                if (id) {
                    editor.composition.insertString(`<div id="${id}">`, selectedRange[0]);
                    editor.composition.insertString('</div>', selectedRange[1] + 1);
                }
            };

            addSectionIdButton.addEventListener('click', addSectionIdListener);

            const buttonGroup = toolbarElement.querySelector('.trix-button-group--block-tools');
            buttonGroup.appendChild(addSectionIdButton);
        }
    }));
});