function loadSlots()
{
    fetch('api/available_slots.php')

    .then(response => response.json())

    .then(data => {

        let html = '';

        data.forEach(slot => {

            html += `
            <option>
            ${slot}
            </option>
            `;
        });

        document.getElementById(
            'slotBox'
        ).innerHTML = html;

    });
}