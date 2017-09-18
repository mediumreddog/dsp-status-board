class app {
  constructor(name) {
    this.name = name;

    this.renderIcon();

    $('#modalTitle').html(name);
  }

  renderIcon() {
    var html = '<div class="appContainer">\
    							<span data-toggle="modal" data-target="#appModal" href="#"><img class="app" src="apps/' + this.name + '/icon.png" /></span>\
    							<div>' + this.name.charAt(0).toUpperCase() + this.name.slice(1) + '</div>\
    						</div>';

    $("#appDock").append(html);
  }

}

$(document).ready(function () {
  new app('weather');
  new app('flickr');

});
