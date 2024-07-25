var url = window.location;

$('ul.nav li ').filter(function () {
    return this.href == url;
}).parent().addClass('active');