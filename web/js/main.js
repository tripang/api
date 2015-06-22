// Получение списка пользователей по критерию (nick, login, email), GETзапрос
$('form#search-form').submit(function (event) {
    var form = $(this),
        search = form.find( "input[name='search']" ).val();

    $.ajax({
        method: "GET",
        url: "/api/user",
        data: { search: search }
    })
    .done(function (users) {
        var searchListHtml = "";
        users.forEach(function(user, i, arr) {
            searchListHtml += "<li>" + user.email + "</li>";
        });
        if (searchListHtml) {
            var searchDivHtml =
                "<p>Найдено пользователей: "
                + users.length
                + "</p><ul>" + searchListHtml + "</ul>";
        } else {
            var searchDivHtml ="<p>Не найдено ни одного пользователя</p>";
        }
        $('#search-rezult').html(searchDivHtml);
    });

    event.preventDefault();
});

// Получение пользователя по id, GETзапрос
$('ul#idList a').on('click', function() {
    $.ajax({
        method: "GET",
        url: "/api/user",
        data: { id: $(this).html() }
    })
    .done(function (user) {
        if (user) {
            alert(
                'Данные пользователя' + '\n' + '\n'
                + 'id: ' + user.id + '\n'
                + 'nick: ' + user.nick + '\n'
                + 'login: ' + user.login + '\n'
                + 'email: ' + user.email
            );
        }
    });
    return false;
});

// Обновление пользователя (изменение ника, email), POSTзапрос
$('#update form').submit(function (event) {
    var form = $(this);

    $.ajax({
        method: "POST",
        url: "/api/user",
        data: {
            'id': $(this).attr('id'),
            'nick': form.find("input[name='nick']").val(),
            'email': form.find("input[name='email']").val()
        }
    })
    .done(function (users) {
        location.reload();
    });

    event.preventDefault();
});

// Выбор типа возвращаемых данных (json или xml, по выбору клиентского приложения)
$('form#api-form input').click(function (event) {
    if ($(this).val() === 'XML') {
        window.location = '/api/user?search=ivan&type=xml';
    } else if ($(this).val() === 'JSON') {
        window.location = '/api/user?search=ivan&type=json';
    }

    event.preventDefault();
});