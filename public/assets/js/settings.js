document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addDenominationdBtn').addEventListener('click', function() {
        const container = document.getElementById('denominationsContainer');

        const lineBreak = document.createElement('br');
        container.appendChild(lineBreak);

        const newInput = document.createElement('input');
        newInput.type = 'number';
        newInput.name = 'amounts[]';
        newInput.className = "form-control";
        container.appendChild(newInput);

    });
});