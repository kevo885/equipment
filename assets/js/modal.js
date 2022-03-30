var user = document.getElementById('viewUser')
user.addEventListener('show.bs.modal', function(event) {
    // Button that triggered the modal
    var button = event.relatedTarget
    // Extract info from data-bs-* attributes
    var id = button.getAttribute('data-bs-id')
    var name = button.getAttribute('data-bs-name')
    var username = button.getAttribute('data-bs-username')
    var dob = button.getAttribute('data-bs-dob')
    var age = button.getAttribute('data-bs-age')
    var phone = button.getAttribute('data-bs-phone')
    var gender = button.getAttribute('data-bs-gender')
    var modal = $(this)
    modal.find('.name').text(name)
    modal.find('.username').text(username)
    modal.find('.phone').text(phone)
    modal.find('.dob').text(dob)
    modal.find('.id').text(id)
    modal.find('.age').text(age)
    modal.find('.gender').text(gender)
})

var course = document.getElementById('viewCourse')
    course.addEventListener('show.bs.modal', function(event) {
        var but = event.relatedTarget
        var desc = but.getAttribute('data-bs-desc')
        var mo = $(this)
        mo.find('.desc').text(desc)
    })