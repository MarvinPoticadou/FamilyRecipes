var available = false
$(".edit-btn").attr("disabled", true)

$(document).ready(function() {

    $(document).on("click touchend", ".fa-edit", function(e) {
        $(".form-group").toggleClass("d-none")
    })

    $(document).on("click touchend", ".edit-avatar", function(e) {
        $(this).off("click")
        $(this).off("touchend")
        $("#avatar").trigger("click")
    })

    $(".edit-btn").on("click touchend", function(e) {
        editUsername($("#username").val())
    })

    $(document).on("keyup", "#username", function(e) {
        verifUsername($("#username").val())

        if(available) {
            $(".edit-btn").attr("disabled", false)
        } else {
            $(".edit-btn").attr("disabled", true)
        }
    })
})

function verifUsername(username) {

    if(username.length > 2) {

        $.post("/user/verif/"+username, {
            val:username
        }, function(data) {
            if (data) {
                if (data.error) {
                    available = false
                    $(".edit-btn").attr("disabled", true)
                    $("#username-error").text(data.errorStr).css("color", "red")
                } else {
                    available = true
                    $(".edit-btn").attr("disabled", false)
                    $("#username-error").text(data.message).css("color", "green")
                }
            }
        })
    } else {
        available = false
        $("#username-error").text("Nom d'utilisateur trop court").css("color", "red")
    }
}

function editUsername(username) {

    if(available) {

        $.post("/user/update/username/"+username, {
            val:username
        }, function(data) {
            if (data) {
                if (data.error) {
                    $("#username-error").text(data.errorStr).css("color", "red")
                } else {
                    $(".user-title").html(data.username)
                    $(".nav-username").text(data.username)
                    $(".form-group").toggleClass("d-none")
                }
            }
        })
    } else {
        $("#username-error").text("Nom d'utilisateur déja pris").css("color", "red")
    }
}