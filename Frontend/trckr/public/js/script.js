/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./public/components/base/script.js":
/*!******************************************!*\
  !*** ./public/components/base/script.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
 // Global components list

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var components = {
  nprogress: {
    selector: 'html',
    styles: './components/nprogress/nprogress.css',
    script: './components/nprogress/nprogress.js',
    init: function init() {
      NProgress.configure({
        template: "<div class=\"bar bar-primary\" role=\"bar\"><div class=\"peg\"></div></div>",
        speed: 500
      });
      NProgress.start();
      window.addEventListener('components:ready', NProgress.done);
      window.addEventListener('error', function () {
        var bar = document.querySelector('#nprogress [role="bar"]');
        if (bar) bar.className = 'bar bar-danger';
      });
    }
  },
  templatePanel: {
    selector: '.template-panel',
    styles: './components/template-panel/template-panel.css'
  },
  theme: {
    selector: 'html',
    script: './components/theme-switcher/theme-switcher.js',
    init: function init() {
      window.theme = new Theme({
        name: 'concrete-navbar',
        mod: 'navbar-dark',
        localStorage: true,
        values: {
          'navbar-color': '#fff',
          'navbar-bg': '#3d3c3c',
          'navbar-hover-color': '#fff',
          'navbar-hover-bg': '#fff',
          'navbar-title-color': '#adb5bd',
          'navbar-panel-bg': '#b5b5b5',
          'navbar-brand-invert': '0%'
        }
      });
    }
  },
  themeSwitcher: {
    selector: '[data-theme]',
    styles: './components/theme-switcher/theme-switcher.css',
    script: './components/theme-switcher/theme-switcher.js',
    dependencies: 'theme',
    init: function init(nodes) {
      var switchStorage = window.localStorage.getItem('concrete-theme-switch');
      nodes.forEach(function (node) {
        var switchName = node.getAttribute('data-theme-switch'),
            switchTheme = JSON.parse(node.getAttribute('data-theme'));

        if (switchStorage) {
          if (switchName === switchStorage) {
            node.classList.add('active');
            window.activeSwitch = node;
          } else {
            node.classList.remove('active');
          }
        } else {
          if (node.classList.contains('active')) {
            window.activeSwitch = node;
            window.localStorage.setItem('concrete-theme-switch', switchName);
          }
        }

        node.addEventListener('click', function () {
          if (window.activeSwitch) {
            window.activeSwitch.classList.remove('active');
          }

          window.activeSwitch = this;
          window.activeSwitch.classList.add('active');
          window.localStorage.setItem('concrete-theme-switch', switchName);
          window.theme.setTheme(switchTheme);
          window.theme.applyTheme();
        });
      });
    }
  },
  pageReveal: {
    selector: '.page',
    init: function init(nodes) {
      window.addEventListener('components:ready', function () {
        window.dispatchEvent(new Event('resize'));
        document.documentElement.classList.add('components-ready');
      });
      window.addEventListener('components:stylesReady', function () {
        nodes.forEach(function (node) {
          setTimeout(function () {
            node.classList.add('page-revealed');
          }, 500);
        });
      });
    }
  },
  pendedIFrame: {
    selector: '[data-pended-iframe]',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var loader = function () {
          node.setAttribute('src', node.getAttribute('data-pended-iframe'));
        }.bind(node);

        window.addEventListener('classSwitching', loader);
        window.addEventListener('components:stylesReady', loader);
      });
    }
  },
  sessionTimeout: {
    selector: '[data-session-timeout]',
    styles: ['./components/modal/modal.css', './components/animate/animate.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/bootstrap/js/popper.js', './components/bootstrap/js/bootstrap.min.js', './components/session-timeout/session-timeout.js'],
    init: function init() {
      var loginPage = 'index.html',
          timer = document.querySelector('[data-session-timer]'),
          btnLogout = document.querySelector('[data-session-logout]'),
          btnStay = document.querySelector('[data-session-stay]'),
          sessionModal = $('#session-modal'),
          countdown = new Counter({
        timeout: 15,
        checkpoint: 10,
        onUpdate: function onUpdate(second) {
          timer.innerText = second;
        },
        onStop: function onStop() {
          window.location.replace(loginPage);
        },
        onCheckpoint: function onCheckpoint() {
          sessionModal.modal('show');
        }
      });
      window.addEventListener('mousemove', function () {
        countdown.start();
      });
      btnStay.addEventListener('click', function () {
        event.preventDefault();
        sessionModal.modal('hide');
        countdown.start();
      });
      btnLogout.addEventListener('click', function () {
        event.preventDefault();
        window.location.replace(loginPage);
      });
      countdown.start();
    }
  },
  highlight: {
    selector: '.highlight',
    styles: './components/highlight/highlight.css',
    script: './components/highlight/highlight.pack.js',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        hljs.highlightBlock(node);
      });
    }
  },
  dualListBox: {
    selector: '.dual-listbox',
    styles: ['./components/button/button.css', './components/input/input.css', './components/mdi/mdi.css', './components/dual-listbox/dual-listbox.css'],
    script: './components/dual-listbox/dual-listbox.min.js',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var instance = new DualListbox(node, {
          availableTitle: node.getAttribute('data-available-title') || 'Available options',
          selectedTitle: node.getAttribute('data-selected-title') || 'I want to use this',
          addButtonText: null,
          removeButtonText: null,
          addAllButtonText: null,
          removeAllButtonText: null
        });
        instance.add_button.classList.add('btn', 'dual-btn-add', 'btn-primary', 'btn-sm');
        instance.add_all_button.classList.add('btn', 'dual-btn-add-all', 'btn-primary', 'btn-sm');
        instance.remove_button.classList.add('btn', 'dual-btn-remove', 'btn-primary', 'btn-sm');
        instance.remove_all_button.classList.add('btn', 'dual-btn-remove-all', 'btn-primary', 'btn-sm');
        instance.search.classList.add('form-control');
      });
    }
  },
  breadcrumbs: {
    selector: '.breadcrumbs',
    styles: './components/breadcrumbs/breadcrumbs.css'
  },
  badge: {
    selector: '.badge',
    styles: './components/badge/badge.css'
  },
  code: {
    selector: 'code',
    styles: './components/code/code.css'
  },
  dropCap: {
    selector: '.drop-cap',
    styles: './components/drop-cap/drop-cap.css'
  },
  media: {
    selector: '.media',
    styles: './components/media/media.css'
  },
  card: {
    selector: '.card',
    styles: './components/card/card.css'
  },
  panel: {
    selector: '.panel',
    styles: './components/panel/panel.css'
  },
  adminPanel: {
    selector: '.panel.admin-panel',
    styles: ['./components/font-awesome/font-awesome.css', './components/admin-panel/admin-panel.css'],
    script: ['./components/admin-panel/class-switch.js', './components/admin-panel/admin-panel.js', './components/multiswitch/multiswitch.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var panelSwitch = node.querySelector('.admin-panel-switch');
        new AdminPanel(_objectSpread(_objectSpread({
          themes: ['default', 'primary', 'secondary', 'success', 'info', 'warning', 'danger', 'light', 'dark']
        }, parseJSON(node.getAttribute('data-admin-panel'))), {}, {
          node: node
        }));

        if (panelSwitch) {
          MultiSwitch({
            node: panelSwitch,
            targets: [node]
          });
        }
      });
    }
  },
  divider: {
    selector: '.divider',
    styles: './components/divider/divider.css'
  },
  blockquote: {
    selector: '.blockquote',
    styles: './components/blockquote/blockquote.css'
  },
  section: {
    selector: 'section',
    styles: './components/section/section.css'
  },
  alert: {
    selector: '.alert',
    styles: './components/alert/alert.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/bootstrap/js/popper.js', './components/bootstrap/js/bootstrap.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).alert();
      });
    }
  },
  alertToggle: {
    selector: '[data-target-alert]',
    script: './components/base/jquery-3.4.1.min.js',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var target = $(node.getAttribute('data-target-alert'));

        if (target.length) {
          node.addEventListener('click', function () {
            if (this.hasAttribute('data-alert-fade')) {
              target.fadeToggle();
            } else {
              target.slideToggle('fast');
            }
          });
        }
      });
    }
  },
  dropdown: {
    selector: '.dropdown-toggle',
    styles: './components/dropdown/dropdown.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/bootstrap/js/popper.js', './components/bootstrap/js/bootstrap.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).dropdown();
      });
    }
  },
  invoice: {
    selector: '.page-invoice',
    styles: './components/invoice/invoice.css'
  },
  button: {
    selector: '.btn',
    styles: './components/button/button.css'
  },
  table: {
    selector: '.table',
    styles: './components/table/table.css'
  },
  list: {
    selector: '[class*="list"]',
    styles: './components/list/list.css'
  },
  progress: {
    selector: '.progress',
    styles: './components/progress/progress.css'
  },
  nav: {
    selector: '.nav',
    styles: './components/nav/nav.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/bootstrap/js/popper.js', './components/bootstrap/js/bootstrap.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).on('click', function (event) {
          event.preventDefault();
          $(this).tab('show');
        });
        $(node).find('a[data-toggle="tab"]').on('shown.bs.tab', function () {
          window.dispatchEvent(new Event('resize'));
        });
      });
    }
  },
  collapse: {
    selector: '.accordion',
    styles: ['./components/font-awesome/font-awesome.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/bootstrap/js/popper.js', './components/bootstrap/js/bootstrap.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).collapse();
      });
    }
  },
  input: {
    selector: '.form-group, .input-group, .form-check, .custom-control, .form-control',
    styles: './components/input/input.css'
  },
  maxlength: {
    selector: '[maxlength]',
    styles: ['./components/badge/badge.css', './components/maxlength/maxlength.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/maxlength/bootstrap-maxlength.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).maxlength({
          alwaysShow: true,
          threshold: 10,
          warningClass: "badge badge-success",
          limitReachedClass: "badge badge-danger",
          appendToParent: true
        });
      });
    }
  },
  typeahead: {
    selector: '[data-typehead]',
    styles: './components/typeahead/typeahead.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/typeahead/typeahead.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var strs = parseJSON(node.getAttribute('data-typehead'));
        $(node).typeahead({
          hint: true,
          highlight: true,
          minLength: 1
        }, {
          limit: 10,
          name: node.getAttribute('placeholder'),
          displayKey: 'value',
          source: function findMatches(q, cb) {
            var matches = [];
            strs.forEach(function (str) {
              if (new RegExp(q, 'i').test(str)) matches.push({
                value: str
              });
            });
            cb(matches);
          }
        });
      });
    }
  },
  select2: {
    selector: '.select2',
    styles: './components/select2/select2.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/select2/select2.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        node = $(node);
        node.select2({
          dropdownParent: $('.page'),
          placeholder: node.attr('data-placeholder') || null,
          minimumResultsForSearch: node.attr('data-minimum-results-search') || Infinity,
          containerCssClass: node.attr('data-container-class') || null,
          dropdownCssClass: node.attr('data-dropdown-class') || null
        });
      });
    }
  },
  fontFiraSans: {
    selector: 'html',
    styles: 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap'
  },
  fontAwesome: {
    selector: '[class*="fa-"]',
    styles: './components/font-awesome/font-awesome.css'
  },
  mdi: {
    selector: '[class*="mdi-"]',
    styles: './components/mdi/mdi.css'
  },
  spinner: {
    selector: '[data-spinner]',
    styles: './components/spinner/spinner.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/base/jquery-ui.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).spinner(_objectSpread({
          min: 0,
          step: 1
        }, parseJSON(node.getAttribute('data-spinner'))));
      });
    }
  },
  timeSpinner: {
    selector: '[data-time-spinner]',
    styles: './components/spinner/spinner.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/base/jquery-ui.min.js', './components/base/globalize.min.js'],
    dependencies: 'spinner',
    init: function init(nodes) {
      $.widget('ui.timespinner', $.ui.spinner, {
        options: {
          step: 60 * 1000,
          // seconds
          page: 60 // hours

        },
        _parse: function _parse(value) {
          if (typeof value === 'string') {
            if (Number(value) == value) return Number(value);else return +Globalize.parseDate(value);
          } else {
            return value;
          }
        },
        _format: function _format(value) {
          return Globalize.format(new Date(value), 't');
        }
      });
      $(nodes).timespinner();
    }
  },
  daterangepiker: {
    selector: '[name="daterange"]',
    styles: ['./components/button/button.css', './components/daterangepicker/daterangepicker.css'],
    script: ['./components/base/moment.min.js', './components/daterangepicker/daterangepicker.min.js'],
    dependencies: 'currentDevice',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var $node = $(node);
        $node.daterangepicker({
          opens: 'left',
          cancelButtonClasses: 'btn-light',
          ranges: $node.attr('data-predefined') ? {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          } : false
        });

        if (device.ios() || device.android()) {
          $node.on('mousedown', function (event) {
            event.preventDefault();
          });
        }
      });
    }
  },
  datetimepicker: {
    selector: '[data-datetimepicker]',
    styles: ['./components/button/button.css', './components/dropdown/dropdown.css', './components/datetimepicker/bootstrap-datetimepicker.css'],
    script: ['./components/base/moment.min.js', './components/base/jquery-3.4.1.min.js', './components/datetimepicker/bootstrap-datetimepicker.min.js'],
    dependencies: 'currentDevice',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var $node = $(node),
            params = parseJSON($node.attr('data-datetimepicker')),
            defaults = {
          format: 'L LT',
          widgetParent: $node.parent().hasClass('input-group') ? $node.parent().parent() : $node.parent()
        };

        if (params.inline && params.target) {
          var $target = $(params.target);
          delete params.target;
          $node.on('dp.change', function (event) {
            $target.val(event.date.format(params.format || 'L LT'));
          });
          params.widgetParent = null;
        }

        if ((device.ios() || device.android()) && !params.inline) {
          var windowClickHandler = function (event) {
            if (!this.data('DateTimePicker').widgetParent()[0].contains(event.target)) {
              this.data('DateTimePicker').hide();
              window.removeEventListener('touchstart', windowClickHandler);
            }
          }.bind($node),
              inputClickHandler = function (event) {
            event.preventDefault();
            this.data('DateTimePicker').show();
            window.addEventListener('touchstart', windowClickHandler);
          }.bind($node);

          params.focusOnShow = false;
          $node.on('mousedown', inputClickHandler);
        }

        $node.datetimepicker(_objectSpread(_objectSpread({}, defaults), params));
      });
    }
  },
  tagManager: {
    selector: '.tag-manager',
    styles: ['./components/badge/badge.css', './components/tag-manager/tag-manager.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/tag-manager/tag-manager.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node.querySelector('.tag-manager-input')).tagsManager({
          tagsContainer: node.querySelector('.tag-manager-container'),
          prefilled: ["Smartphone", "Apple", "Notebook", "Gadget"],
          tagClass: 'badge badge-success'
        });
      });
    }
  },
  tooltip: {
    selector: '[data-toggle="tooltip"]',
    styles: './components/tooltip/tooltip.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/bootstrap/js/popper.js', './components/bootstrap/js/bootstrap.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).tooltip();
      });
    }
  },
  popover: {
    selector: '[data-toggle="popover"]',
    styles: './components/popover/popover.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/bootstrap/js/popper.js', './components/bootstrap/js/bootstrap.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).popover();
      });
    }
  },
  classSwitcher: {
    selector: '[data-class-switcher]',
    init: function init(nodes) {
      window.classSwitcher = {};
      nodes.forEach(function (node) {
        var group,
            params = parseJSON(node.getAttribute('data-class-switcher')),
            container = document.querySelector(params.group),
            target = container.querySelector(params.target);

        if (!window.classSwitcher[params.group]) {
          window.classSwitcher[params.group] = {
            targetClass: 'active',
            triggerClass: 'active',
            container: container,
            triggers: [],
            targets: [],
            lastTrigger: null
          };
        }

        group = window.classSwitcher[params.group];
        group.triggers.push(node);
        group.targets.push(target);
        if (params.targetClass) group.targetClass = params.targetClass;
        if (params.triggerClass) group.triggerClass = params.triggerClass;
        if (node.classList.contains(group.triggerClass)) group.lastTrigger = node;
        node.classSwitcher = {
          group: group,
          target: target
        };
        node.addEventListener('click', function () {
          var group = this.classSwitcher.group,
              event = new CustomEvent('classSwitching', {
            bubbles: true
          });
          event.triggerTarget = this.classSwitcher.target;
          group.lastTrigger.classList.remove(group.triggerClass);
          group.lastTrigger.classSwitcher.target.classList.remove(group.targetClass);
          this.classList.add(group.triggerClass);
          this.classSwitcher.target.classList.add(group.targetClass);
          group.lastTrigger = this;
          this.dispatchEvent(event);
        });
      });
    }
  },
  modalBtn: {
    selector: '[data-modal-trigger]',
    dependencies: 'modal',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var params = parseJSON(node.getAttribute('data-modal-trigger'));
        node.addEventListener('click', function () {
          var modal = document.querySelector(params.target);
          if (modal.animClass) modal.classList.remove(modal.animClass);
          modal.animClass = params.animClass;
          if (modal.animClass) modal.classList.add(modal.animClass);

          if (modal.classList.contains('show')) {
            $(modal).modal('hide');
          } else {
            $(modal).modal('show');
          }
        });
      });
    }
  },
  modal: {
    selector: '.modal',
    styles: ['./components/modal/modal.css', './components/animate/animate.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/bootstrap/js/popper.js', './components/bootstrap/js/bootstrap.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).modal({
          show: false,
          focus: false
        });
      });
    }
  },
  colorpicker: {
    selector: '[data-colorpick]',
    styles: ['./components/popover/popover.css', './components/colorpicker/colorpicker.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/bootstrap/js/popper.js', './components/bootstrap/js/bootstrap.min.js', './components/colorpicker/colorpicker.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).colorpicker(_objectSpread({
          fallbackColor: '#000'
        }, parseJSON(node.getAttribute('data-colorpick'))));
      });
    }
  },
  maskedinput: {
    selector: '[data-masked]',
    script: ['./components/base/jquery-3.4.1.min.js', './components/maskedinput/jquery.maskedinput.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).mask(node.getAttribute('data-masked'));
      });
    }
  },
  ckeditor: {
    selector: '[data-ckeditor]',
    styles: './components/ckeditor/ckeditor.css',
    script: './components/ckeditor/ckeditor.min.js',
    init: function init(nodes) {
      var promises = [];
      nodes.forEach(function (node) {
        switch (node.getAttribute('data-ckeditor')) {
          case 'inline':
            promises.push(CKEDITOR.InlineEditor.create(node));
            break;

          case 'balloon':
            promises.push(CKEDITOR.BalloonEditor.create(node));
            break;

          case 'document':
            promises.push(CKEDITOR.DecoupledEditor.create(node.querySelector('.document-editor-content')).then(function (editor) {
              node.querySelector('.document-editor-toolbar').appendChild(editor.ui.view.toolbar.element);
            }));
            break;

          default:
            promises.push(CKEDITOR.ClassicEditor.create(node));
            break;
        }
      });
      return Promise.all(promises);
    }
  },
  close: {
    selector: '.close',
    styles: './components/close/close.css'
  },
  summernote: {
    selector: '.summernote',
    styles: ['./components/button/button.css', './components/input/input.css', './components/table/table.css', './components/close/close.css', './components/dropdown/dropdown.css', './components/tooltip/tooltip.css', './components/popover/popover.css', './components/modal/modal.css', './components/card/card.css', './components/summernote/summernote.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/bootstrap/js/popper.js', './components/bootstrap/js/bootstrap.min.js', './components/summernote/summernote.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).summernote(_objectSpread({
          airMode: false,
          placeholder: 'Write here...',
          blockquoteBreakingLevel: 1,
          focus: false
        }, parseJSON(node.getAttribute('data-summernote-opts'))));
      });
    }
  },
  markdown: {
    selector: '.markdown',
    styles: ['./components/markdown/markdown.css', './components/button/button.css', './components/blockquote/blockquote.css', './components/font-awesome/font-awesome.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/markdown/markdown.js', './components/markdown/to-markdown.js', './components/markdown/bootstrap-markdown.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).markdown({
          iconlibrary: 'fa',
          height: '350',
          resize: 'vertical',
          footer: node.getAttribute('data-markdown-footer') ? node.getAttribute('data-markdown-footer') : null
        });
      });
    }
  },
  nestable: {
    selector: '.nestable',
    styles: ['./components/font-awesome/font-awesome.css', './components/nestable/nestable.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/nestable/nestable.js'],
    init: function init(nodes) {
      $(nodes).nestable({
        expandBtnHTML: '<button data-action="expand" type="button" class="dd-button fa-chevron-right"></button>',
        collapseBtnHTML: '<button data-action="collapse" type="button" class="dd-button fa-chevron-down"></button>'
      });
      $('[data-nestable-action]').on('click', function () {
        var obj = parseJSON(this.getAttribute('data-nestable-action'));

        for (var key in obj) {
          $(obj[key]).nestable(key);
        }
      });
    }
  },
  jstree: {
    selector: '.jstree',
    styles: ['./components/font-awesome/font-awesome.css', './components/jstree/jstree.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/jstree/jstree.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        node = $(node);
        node.jstree({
          types: {
            "#": {
              "valid_children": ["root"]
            },
            "root": {
              "icon": "fa fa-folder-o",
              "valid_children": ["default"]
            },
            "default": {
              "icon": "fa fa-file-o",
              "valid_children": ["default", "file"]
            },
            "file": {
              "icon": "fa fa-file-o",
              "valid_children": []
            }
          },
          "core": {
            "check_callback": true
          },
          plugins: node.attr('data-jstree-plugin') ? node.attr('data-jstree-plugin').split(' ') : null
        });

        if (node.attr('data-jstree-plugin') === 'search') {
          var timeoutId = false;
          $('.jstree-search').keyup(function () {
            if (timeoutId) clearTimeout(timeoutId);
            timeoutId = setTimeout(function () {
              var value = $('.jstree-search').val();
              node.jstree(true).search(value);
            }, 250);
          });
        }
      });
    }
  },
  cropper: {
    selector: '[data-cropper-container]',
    styles: ['./components/font-awesome/font-awesome.css', './components/cropper/cropper.css'],
    script: './components/cropper/cropper.min.js',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var image = node.querySelector('.img-container img'),
            options = {
          aspectRatio: 16 / 9,
          preview: node.querySelectorAll('.img-preview')
        },
            cropper = new Cropper(image, options);
        node.querySelectorAll('[data-cropper]').forEach(function (node) {
          node.addEventListener('click', function () {
            var params = {},
                target = null,
                cropBoxData = cropper.getCropBoxData(),
                canvasData = cropper.getCanvasData(); // Get parameters from data-attribute

            try {
              params = JSON.parse(this.getAttribute('data-cropper'));
            } catch (error) {} // Extract target from parameters


            if (params.target) {
              target = document.querySelector(params.target);
              delete params.target;
            }

            for (var key in params) {
              // Get canvas handling
              if (key === 'getCroppedCanvas') {
                var result = cropper[key](params[key]).toDataURL('image/jpeg');
                target.querySelector('[data-cropper-result]').setAttribute('src', result);
                target.querySelector('[data-cropper-download]').setAttribute('href', result);
                $(target).modal('show');
              } // Get data methods handling


              if (['getData', 'getContainerData', 'getImageData', 'getCanvasData', 'getCropBoxData'].indexOf(key) !== -1) {
                target.value = JSON.stringify(cropper[key]());
              } // Set data methods handling


              if (['setData', 'setCanvasData', 'setCropBoxData'].indexOf(key) !== -1) {
                cropper[key](parseJSON(target.value));
              } // Other methods handling


              if (['crop', 'reset', 'clear', 'replace', 'enable', 'disable', 'destroy', 'move', 'moveTo', 'zoom', 'zoomTo', 'rotate', 'rotateTo', 'scale', 'scaleX', 'scaleY', 'setAspectRatio', 'setDragMode'].indexOf(key) !== -1) {
                if (params[key] instanceof Array) cropper[key](params[key][0], params[key][1]);else cropper[key](params[key]);
              } // Options handling


              if (['viewMode', 'responsive', 'restore', 'checkCrossOrigin', 'checkOrientation', 'modal', 'guides', 'center', 'highlight', 'background', 'autoCrop', 'movable', 'rotatable', 'scalable', 'zoomable', 'zoomOnTouch', 'zoomOnWheel', 'cropBoxMovable', 'cropBoxResizable', 'toggleDragModeOnDblclick'].indexOf(key) !== -1) {
                if (params[key] === 'checkbox') options[key] = this.checked;else options[key] = params[key];

                options.ready = function () {
                  cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
                };

                cropper.destroy();
                cropper = new Cropper(image, options);
              }
            }
          });
        });
      });
    }
  },
  fileupload: {
    selector: '.tower-file-input',
    styles: ['./components/mdi/mdi.css', './components/tower-file-input/tower-file-input.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/tower-file-input/tower-file-input.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).fileInput(_objectSpread({
          iconClass: 'mdi-upload'
        }, parseJSON(node.getAttribute('data-tfi-opts'))));
      });
    }
  },
  easyzoom: {
    selector: '.easyzoom',
    styles: './components/easyzoom/easyzoom.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/easyzoom/easyzoom.min.js'],
    init: function init(nodes) {
      $(nodes).easyZoom();
    }
  },
  xzoom: {
    selector: '.xzoom',
    styles: './components/xzoom/xzoom.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/xzoom/xzoom.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).xzoom(parseJSON(node.getAttribute('data-xzoom-opts')));
      });
    }
  },
  slick: {
    selector: '.slick-slider',
    styles: ['./components/slick/slick.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/slick/slick.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var breakpoint = {
          sm: 576,
          md: 768,
          lg: 992,
          xl: 1200,
          xxl: 1600
        },
            // slick slider uses desktop first principle
        responsive = []; // Making the inheritable parameters

        for (var key in breakpoint) {
          if (node.hasAttribute("data-slick-".concat(key))) {
            responsive.push({
              breakpoint: breakpoint[key],
              settings: parseJSON(node.getAttribute("data-slick-".concat(key)))
            });
          }
        }

        $(node).slick({
          responsive: responsive
        });
        window.addEventListener('resize', function () {
          $(node).slick('refresh');
        });
      });
      var addButton = document.querySelector('.add-slide'),
          removeButton = document.querySelector('.remove-slide'),
          filterButton = document.querySelector('.filter-slide'); // Add sample

      if (addButton) addButton.addEventListener('click', function () {
        var target = document.querySelector(this.getAttribute('data-target-slick'));
        $(target).slick('slickAdd', "<div><div><div><div class=\"slide-content\"><h1>".concat(target.slick.$slides.length + 1, "</h1></div></div></div></div>"));
      }); // Remove sample

      if (removeButton) removeButton.addEventListener('click', function () {
        var target = document.querySelector(this.getAttribute('data-target-slick'));
        $(target).slick('slickRemove', target.slick.$slides.length - 1);
      }); // Filter sample

      if (filterButton) filterButton.addEventListener('click', function () {
        var target = document.querySelector(this.getAttribute('data-target-slick'));

        if (target.getAttribute('data-filtered') === 'true') {
          $(target).slick('slickUnfilter');
          this.innerText = 'Filter Slides';
          target.setAttribute('data-filtered', 'false');
        } else {
          $(target).slick('slickFilter', ':even');
          this.innerText = 'Unfilter Slides';
          target.setAttribute('data-filtered', 'true');
        }
      }); // Hidden slick bug fix

      var switches = document.querySelectorAll('[data-class-switcher]');
      if (switches.length) switches.forEach(function (trigger) {
        trigger.addEventListener('classSwitching', function (event) {
          $('.slick-slider', event.triggerTarget).slick('setPosition');
        });
      });
    }
  },
  dropzone: {
    selector: '.dropzone-form',
    styles: './components/dropzone/dropzone.css',
    script: './components/dropzone/dropzone.min.js',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        new Dropzone(node, _objectSpread({
          paramName: "file",
          addRemoveLinks: true,
          dictRemoveFile: '<span class="fa-close"></span>',
          autoQueue: true
        }, parseJSON(node.getAttribute('data-dropzone'))));
      });
    }
  },
  fullscreen: {
    selector: '[data-fullscreen]',
    styles: './components/fullscreen/fullscreen.css',
    dependencies: 'currentDevice',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        if (device.macos()) {
          node.addEventListener('click', function () {
            if (document.webkitFullscreenElement) {
              document.webkitExitFullscreen();
            } else {
              document.querySelector(this.getAttribute('data-fullscreen')).webkitRequestFullscreen();
            }
          });
        } else {
          node.addEventListener('click', function () {
            if (document.fullscreenElement) {
              document.exitFullscreen();
            } else {
              document.querySelector(this.getAttribute('data-fullscreen')).requestFullscreen();
            }
          });
        }
      });
    }
  },
  animationSample: {
    selector: '[data-animation-sample]',
    styles: './components/animate/animate.css',
    init: function init(nodes) {
      var timeoutId = null,
          endCb = null,
          target = document.querySelector('#animation-sample');
      nodes.forEach(function (node) {
        node.addEventListener('click', function () {
          if (timeoutId) clearTimeout(timeoutId);
          if (endCb) endCb();
          var animationClass = this.getAttribute('data-animation-sample');
          target.classList.add(animationClass);

          endCb = function endCb() {
            target.classList.remove(animationClass);
          };

          timeoutId = setTimeout(endCb, 1000);
        });
      });
    }
  },
  pnotify: {
    selector: '.pnotify-button',
    styles: ['./components/alert/alert.css', './components/font-awesome/font-awesome.css', './components/pnotify/pnotify.css'],
    script: ['./components/pnotify/PNotify.min.js', './components/pnotify/PNotifyButtons.min.js'],
    init: function init(nodes) {
      PNotify.defaultStack.spacing1 = 10;
      PNotify.defaultStack.spacing2 = 10;
      PNotify.defaultStack.firstpos1 = 10;
      PNotify.defaultStack.firstpos2 = 10;
      PNotify.defaultStack.push = 'top';
      var Stacks = {
        stackTopLeft: _objectSpread(_objectSpread({}, PNotify.defaultStack), {}, {
          dir1: 'down',
          dir2: 'right'
        }),
        stackBottomLeft: _objectSpread(_objectSpread({}, PNotify.defaultStack), {}, {
          dir1: 'up',
          dir2: 'right'
        }),
        stackBottomRight: _objectSpread(_objectSpread({}, PNotify.defaultStack), {}, {
          dir1: 'up',
          dir2: 'left'
        }),
        stackCenterTop: _objectSpread(_objectSpread({}, PNotify.defaultStack), {}, {
          dir1: 'down',
          dir2: null
        }),
        stackCenterBottom: _objectSpread(_objectSpread({}, PNotify.defaultStack), {}, {
          dir1: 'up',
          dir2: null
        })
      };
      PNotify.styling.bootstrap4 = _objectSpread(_objectSpread({}, PNotify.styling.bootstrap4), {}, {
        primary: 'alert-primary',
        secondary: 'alert-secondary',
        warning: 'alert-warning',
        danger: 'alert-danger',
        dark: 'alert-dark',
        light: 'alert-light'
      });
      PNotify.icons.fontawesome4 = _objectSpread(_objectSpread({}, PNotify.icons.fontawesome4), {}, {
        primary: 'fa fa-exclamation-triangle',
        secondary: 'fa fa-exclamation-triangle',
        warning: 'fa fa-exclamation-triangle',
        danger: 'fa fa-exclamation-triangle',
        dark: 'fa fa-exclamation-triangle',
        light: 'fa fa-exclamation-triangle'
      });
      nodes.forEach(function (node) {
        node.addEventListener('click', function () {
          PNotify.alert({
            type: this.getAttribute('data-pnotify-theme') || 'primary',
            title: this.getAttribute('data-pnotify-title') || 'Default title',
            text: this.getAttribute('data-pnotify-text') || 'Default text',
            animation: this.getAttribute('data-pnotify-animation') || 'fade',
            styling: 'bootstrap4',
            icons: 'fontawesome4',
            width: '300px',
            stack: this.getAttribute('data-pnotify-stack') ? Stacks[this.getAttribute('data-pnotify-stack')] : PNotify.defaultStack,
            addClass: this.getAttribute('data-pnotify-modifier') ? this.getAttribute('data-pnotify-modifier') : '',
            shadow: !this.hasAttribute('data-pnotify-no-shadow')
          });
        });
      });
    }
  },
  pagination: {
    selector: '.pagination',
    styles: './components/pagination/pagination.css'
  },
  datatables: {
    selector: '.data-table',
    styles: ['./components/datatables/datatables.css', './components/pagination/pagination.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/base/jquery-ui.min.js', './components/datatables/datatables.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var opts = {
          searching: node.hasAttribute('data-table-searching'),
          lengthChange: node.getAttribute('data-table-lengthChange'),
          pageLength: node.getAttribute('data-page-length') || 10,
          lengthMenu: [5, 10, 25, 50, 75, 100],
          dom: "<'row row-10'<'col-sm-12 col-md-6 pl-3'l><'col-sm-12 col-md-6 pr-3'f>>\n\t\t\t\t\t\t\t<'row'<'col-sm-12'Rt>>\n\t\t\t\t\t\t\t<'row row-10'<'col-sm-12 col-md-5 pl-3'i><'col-sm-12 col-md-7 pr-3'p>>",
          select: {
            className: 'form-control form-control-sm'
          }
        };

        function format(d) {
          // `d` is the original data object for the row
          return "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" style=\"padding-left:50px;\">\n\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t<td>Full name:</td>\n\t\t\t\t\t\t\t<td>".concat(d.name, "</td>\n\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t<td>Extension number:</td>\n\t\t\t\t\t\t\t<td>").concat(d.extn, "</td>\n\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t<td>Extra info:</td>\n\t\t\t\t\t\t\t<td>And any further details here (images etc)...</td>\n\t\t\t\t\t\t</tr>\n\t\t\t\t\t</table>");
        }

        switch (node.getAttribute('data-table-mode')) {
          case 'print':
            opts = _objectSpread(_objectSpread({}, opts), {}, {
              dom: "<'row row-10'<'col-sm-12 col-md-6 pl-3'B><'col-sm-12 col-md-6 pr-3'f>>\n\t\t\t\t\t\t\t<'row'<'col-sm-12'tr>>\n\t\t\t\t\t\t\t<'row row-10'<'col-sm-12 col-md-5 pl-3'i><'col-sm-12 col-md-7 pr-3'p>>",
              buttons: [{
                extend: 'print',
                className: 'btn-dark',
                text: 'Print current page',
                exportOptions: {
                  modifier: {
                    page: 'current'
                  }
                }
              }]
            });
            $(node).DataTable(opts);
            break;

          case 'multi-filter':
            $(node).find('tfoot th').each(function () {
              $(this).html('<input class="form-control form-control-sm multiple-search" type="text" placeholder="Search ' + $(this).text() + '" />');
            });
            opts = _objectSpread(_objectSpread({}, opts), {}, {
              ordering: false,
              searching: true
            }); // Apply the search

            var table = $(node).DataTable(opts);
            table.columns().every(function () {
              var that = this;
              $('input', this.footer()).on('keyup change', function () {
                if (that.search() !== this.value) {
                  that.search(this.value).draw();
                }
              });
            });
            break;

          case 'row-grouping':
            var groupColumn = 2,
                table1 = $(node).DataTable(_objectSpread(_objectSpread({}, opts), {}, {
              "columnDefs": [{
                "visible": false,
                "targets": groupColumn
              }],
              "order": [[groupColumn, 'asc']],
              "displayLength": 25,
              "drawCallback": function drawCallback(settings) {
                var api = this.api();
                var rows = api.rows({
                  page: 'current'
                }).nodes();
                var last = null;
                api.column(groupColumn, {
                  page: 'current'
                }).data().each(function (group, i) {
                  if (last !== group) {
                    $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                    last = group;
                  }
                });
              }
            })); // Order by the grouping

            $(node).find('tbody').on('click', 'tr.group', function () {
              var currentOrder = table1.order()[0];

              if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
                table1.order([groupColumn, 'desc']).draw();
              } else {
                table1.order([groupColumn, 'asc']).draw();
              }
            });
            break;

          case 'col-reorder':
            $(node).DataTable({
              dom: "<'row row-10'<'col-sm-12 col-md-6 pl-3'l><'col-sm-12 col-md-6 pr-3'f>>\n\t\t\t\t\t\t\t<'row'<'col-sm-12'Rt>>\n\t\t\t\t\t\t\t<'row row-10'<'col-sm-12 col-md-5 pl-3'i><'col-sm-12 col-md-7 pr-3'p>>"
            });
            break;

          case 'collapse':
            var table2 = $(node).DataTable(_objectSpread(_objectSpread({}, opts), {}, {
              "ajax": "components/datatables/objects.txt",
              "columns": [{
                "className": 'details-control text-center h3 text-primary',
                "orderable": false,
                "data": null,
                "defaultContent": '+'
              }, {
                "data": "name"
              }, {
                "data": "position"
              }, {
                "data": "office"
              }, {
                "data": "salary"
              }],
              "order": [[1, 'asc']]
            })); // Add event listener for opening and closing details

            $(node).find('tbody').on('click', 'td.details-control', function () {
              var tr = $(this).closest('tr');
              var row = table2.row(tr);

              if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
              } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
              }
            });
            break;

          default:
            $(node).DataTable(opts);
            break;
        }
      });
    }
  },
  driver: {
    selector: '[data-driver]',
    styles: './components/driver/driver.css',
    script: './components/driver/driver.min.js',
    init: function init(nodes) {
      var defaults = {
        animate: true,
        opacity: 0.75,
        stageBackground: '#fff',
        nextBtnText: 'Next',
        prevBtnText: 'Previous'
      },
          start = document.querySelector('[data-driver-start]'),
          steps = [],
          driver;
      nodes.forEach(function (node) {
        steps.push({
          element: node,
          popover: parseJSON(node.getAttribute('data-driver'))
        });
      });
      driver = window.driver = new Driver(defaults);
      driver.defineSteps(steps);

      if (start) {
        start.addEventListener('click', function (event) {
          event.stopPropagation();
          driver.start();
        });
      } else {
        setTimeout(function () {
          driver.start();
        }, 5000);
      }
    }
  },
  nprogressSample: {
    selector: '[data-nprogress]',
    dependencies: 'nprogress',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        node.addEventListener('click', function () {
          var params = parseJSON(this.getAttribute('data-nprogress'));

          for (var key in params) {
            NProgress[key](params[key]);
          }
        });
      });
    }
  },
  countdown: {
    selector: '.countdown',
    styles: './components/countdown/countdown.css',
    script: ['./components/base/util.min.js', './components/progress-circle/progress-circle.js', './components/countdown/countdown.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var countdown = aCountdown({
          node: node,
          from: node.getAttribute('data-from'),
          to: node.getAttribute('data-to'),
          count: node.getAttribute('data-count'),
          tick: 100
        });
      });
    }
  },
  progressCircle: {
    selector: '.progress-circle-wrap',
    styles: './components/progress-circle/progress-circle.css',
    script: ['./components/base/util.min.js', './components/progress-circle/counter.js', './components/progress-circle/progress-circle.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var counter = aCounter({
          node: node.querySelector('.progress-counter'),
          duration: 500,
          formatter: function formatter(value) {
            return value + '%';
          }
        }),
            progress = aProgressCircle({
          node: node.querySelector('.progress-circle')
        }),
            countHandler = function (event) {
          this.render(event.value * 3.6);
        }.bind(progress);

        counter.params.node.addEventListener('counterUpdate', countHandler);
        counter.run();
      });
    }
  },
  particles: {
    selector: '#particles-container',
    styles: './components/particles/particles.css',
    script: ['./components/base/util.min.js', './components/particles/particles.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var defaults = {
          'particles': {
            'number': {
              'value': 75,
              'density': {
                'enable': true,
                'value_area': 800
              }
            },
            'color': {
              'value': '#85e2ff'
            },
            'shape': {
              'type': 'circle',
              'stroke': {
                'width': 0,
                'color': '#85e2ff'
              },
              'polygon': {
                'nb_sides': 5
              },
              'image': {
                'src': 'img/github.svg',
                'width': 100,
                'height': 100
              }
            },
            'opacity': {
              'value': 0.25,
              'random': false,
              'anim': {
                'enable': false,
                'speed': 1,
                'opacity_min': 0.1,
                'sync': false
              }
            },
            'size': {
              'value': 3,
              'random': true,
              'anim': {
                'enable': false,
                'speed': 40,
                'size_min': 0.1,
                'sync': false
              }
            },
            'line_linked': {
              'enable': true,
              'distance': 220,
              'color': '#85e2ff',
              'opacity': 0.4,
              'width': 1
            },
            'move': {
              'enable': true,
              'speed': 3,
              'direction': 'none',
              'random': false,
              'straight': false,
              'out_mode': 'out',
              'bounce': false,
              'attract': {
                'enable': false,
                'rotateX': 600,
                'rotateY': 1200
              }
            }
          },
          'interactivity': {
            'detect_on': 'canvas',
            'events': {
              'onhover': {
                'enable': true,
                'mode': 'grab'
              },
              'onclick': {
                'enable': true,
                'mode': ''
              },
              'resize': true
            },
            'modes': {
              'grab': {
                'distance': 140,
                'line_linked': {
                  'opacity': 1
                }
              },
              'bubble': {
                'distance': 400,
                'size': 40,
                'duration': 2,
                'opacity': 8,
                'speed': 3
              },
              'repulse': {
                'distance': 100,
                'duration': 0.4
              },
              'push': {
                'particles_nb': 4
              },
              'remove': {
                'particles_nb': 2
              }
            }
          },
          'retina_detect': true
        },
            custom = parseJSON(node.getAttribute('data-particles'));
        particlesJS('particles-container', Util.merge([defaults, custom]));
      });
    }
  },
  searchResult: {
    selector: '.search-result',
    styles: './components/search-result/search-result.css'
  },
  timeline: {
    selector: '.timeline',
    styles: './components/timeline/timeline.css',
    init: function init(nodes) {
      function resizeHandler() {
        var items = this.querySelectorAll('.timeline-item'),
            tmp = [null, null];
        items.forEach(function (item, i) {
          var top = item.offsetTop,
              offset = Math.ceil(item.getBoundingClientRect().height + top);
          item.classList.remove('timeline-right');
          item.classList.remove('timeline-left');

          if (i === 0) {
            item.classList.add('timeline-left');
            top = item.offsetTop;
            offset = Math.ceil(item.getBoundingClientRect().height + top);
            tmp[0] = tmp[1];
            tmp[1] = offset;
          } else if (i === 1) {
            item.classList.add('timeline-right');
            top = item.offsetTop;
            offset = Math.ceil(item.getBoundingClientRect().height + top);
            tmp[0] = tmp[1];
            tmp[1] = offset;
          } else if (tmp[0] > tmp[1]) {
            item.classList.add('timeline-right');
            top = item.offsetTop;
            offset = Math.ceil(item.getBoundingClientRect().height + top);
            tmp[1] = offset;
          } else {
            item.classList.add('timeline-left');
            top = item.offsetTop;
            offset = Math.ceil(item.getBoundingClientRect().height + top);
            tmp[0] = offset;
          }
        });
      }

      nodes.forEach(function (node) {
        if (node.hasAttribute('data-auto-timeline')) {
          setTimeout(function () {
            resizeHandler.call(node);
            node.classList.add('timeline-ready');
            window.addEventListener('resize', resizeHandler.bind(node));
          }, 500);
        }
      });
    }
  },
  lightgallery: {
    selector: '[data-lightgallery]',
    styles: './components/lightgallery/lightgallery.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/lightgallery/lightgallery.min.js', './components/base/util.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        node = $(node);
        var defaultSettings = {
          thumbnail: true,
          selector: '.lightgallery-item',
          youtubePlayerParams: {
            modestbranding: 1,
            showinfo: 0,
            rel: 0,
            controls: 0
          },
          vimeoPlayerParams: {
            byline: 0,
            portrait: 0,
            color: 'A90707'
          }
        },
            customSettings = parseJSON(node.attr('data-lightgallery'));
        node.lightGallery(Util.merge([defaultSettings, customSettings]));
      });
    }
  },
  fullcalendar: {
    selector: '.fullcalendar',
    styles: ['./components/fullcalendar/fullcalendar.css', './components/button/button.css', './components/table/table.css', './components/alert/alert.css', './components/card/card.css', './components/tooltip/tooltip.css', './components/font-awesome/font-awesome.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/base/jquery-ui.min.js', './components/base/moment.min.js', './components/fullcalendar/fullcalendar.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $('.external-events .fc-event').each(function () {
          $(this).data('event', {
            title: $.trim($(this).text()),
            stick: true,
            className: 'fc-event-' + $(this).attr('data-event')
          });
          $(this).draggable({
            zIndex: 999,
            revert: true,
            revertDuration: 0
          });
        });
        $(node).fullCalendar({
          themeSystem: 'bootstrap4',
          header: _objectSpread({
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          }, parseJSON(node.getAttribute('data-fullcalendar-header'))),
          editable: true,
          droppable: true,
          drop: function drop() {
            // is the "remove after drop" checkbox checked?
            if (!$(this).hasClass('event-recurring')) {
              $(this).remove();
            }
          },
          eventRender: function eventRender(event, element) {
            $(element).append("<span class='event-close fa-times'></span>");
            $(element).find('.event-close').click(function () {
              $(node).fullCalendar('removeEvents', event._id);
            });
          },
          weekNumbers: false,
          weekNumbersWithinDays: true,
          eventLimit: true,
          events: node.hasAttribute('data-fullcalendar-event') ? parseJSON(node.getAttribute('data-fullcalendar-event')) : null
        });
      });
    }
  },
  jvectorMap: {
    selector: '.jvector-map-wrap',
    styles: './components/jvector-map/jvector-map.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/jvector-map/jvector-map.js', './components/jvector-map/assets/world-mill.js', './components/jvector-map/assets/us-lcc-en.js'],
    init: function init(nodes) {
      nodes.forEach(function (container) {
        var map = new jvm.Map(_objectSpread({
          container: $(container).find('.jvector-map-body')
        }, parseJSON(container.getAttribute('data-jvector-map')))); // flag select toggles

        $(container).find('.jvector-flaq').on('click', function () {
          map.setFocus({
            region: this.getAttribute('data-loc'),
            animate: true
          });
        }); // list selection

        $(container).find('.jvector-map-list').on('change', function () {
          map.setFocus({
            region: this.value,
            animate: true
          });
        });
      });
    }
  },
  flotchart: {
    selector: '.flotchart-container',
    styles: './components/flotchart/flotchart.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/flotchart/flotchart.js', './components/flotchart/flotchart-resize.js', './components/flotchart/flotchart-pie.js', './components/flotchart/flotchart-tooltip.js'],
    init: function init(nodes) {
      var options = {
        colors: ['#6d92b9', '#f8c450', '#00c3aa', '#ee6161', '#6cd1e1', '#967adc'],
        grid: {
          show: true,
          aboveData: true,
          color: '#ddd',
          clickable: true,
          hoverable: true
        },
        xaxis: {
          color: '#b5b5b5' // color for value in flotchart.scss

        },
        yaxis: {
          color: '#b5b5b5' // color for value in flotchart.scss

        },
        tooltip: {
          show: true,
          content: '%x : %y.0',
          defaultTheme: false
        },
        series: {
          lines: {
            lineWidth: 2
          },
          bars: {
            fillColor: {
              colors: [{
                opacity: 0.8
              }, {
                opacity: 1
              }]
            }
          }
        }
      };
      nodes.forEach(function (node) {
        $.plot($(node), JSON.parse(node.getAttribute('data-flotchart-data')), JSON.parse(node.getAttribute('data-flotchart-options')) || options);
      });
    }
  },
  currentDevice: {
    selector: 'html',
    script: ['./components/current-device/current-device.min.js']
  },
  rdNavbar: {
    selector: '.rd-navbar',
    styles: ['./components/font-awesome/font-awesome.css', './components/rd-navbar/rd-navbar.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/rd-navbar/rd-navbar.min.js'],
    dependencies: 'currentDevice',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).RDNavbar({
          stickUpClone: false,
          anchorNav: false,
          autoHeight: false,
          responsive: {
            0: {
              layout: 'rd-navbar-fixed',
              deviceLayout: 'rd-navbar-fixed',
              stickUp: false
            },
            992: {
              layout: 'rd-navbar-fixed',
              deviceLayout: 'rd-navbar-fixed',
              stickUp: false
            }
          }
        });
      });
    }
  },
  sidebar: {
    selector: '.sidebar',
    styles: './components/sidebar/sidebar.css'
  },
  mobileSidebar: {
    selector: '.mobile-sidebar',
    styles: './components/mobile-sidebar/mobile-sidebar.css'
  },
  highchartsDouble: {
    selector: '[data-highcharts-double="container"]',
    styles: './components/highcharts/highcharts.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/highcharts/highcharts.js', './components/highcharts/highcharts-double.init.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $.getJSON(node.getAttribute('data-file'), initHighchartsDouble.bind(node));
      });
    }
  },
  highchartsSparkline: {
    selector: '[data-sparkline]',
    styles: './components/highcharts/highcharts.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/highcharts/highcharts.js'],
    init: function init(nodes) {
      var defaults = {
        chart: {
          type: 'area',
          margin: [12, 10, 12, 10],
          height: 50,
          style: {
            overflow: 'visible',
            'min-width': '150px',
            'max-width': '300px'
          },
          // small optimalization, saves 1-2 ms each sparkline
          skipClone: true
        },
        title: {
          text: null
        },
        credits: {
          enabled: false
        },
        xAxis: {
          labels: {
            enabled: false
          },
          title: {
            text: null
          },
          lineColor: '#b2b2b2',
          startOnTick: false,
          endOnTick: false,
          tickPositions: []
        },
        yAxis: {
          endOnTick: false,
          startOnTick: false,
          gridLineColor: '#b2b2b2',
          labels: {
            enabled: false
          },
          title: {
            text: null
          },
          tickPositions: [0]
        },
        legend: {
          enabled: false
        },
        tooltip: {
          hideDelay: 0,
          outside: false,
          shared: true,
          padding: 5,
          headerFormat: null,
          footerFormat: null,
          pointFormat: '{point.y}'
        },
        plotOptions: {
          series: {
            animation: false,
            lineWidth: 1,
            shadow: false,
            states: {
              hover: {
                lineWidth: 1
              }
            },
            marker: {
              radius: 1,
              states: {
                hover: {
                  radius: 2
                }
              }
            },
            fillOpacity: 0.25
          },
          column: {
            negativeColor: '#ee6161',
            borderColor: 'transparent'
          }
        }
      };
      nodes.forEach(function (node) {
        Highcharts.chart(node, Highcharts.merge(defaults, parseJSON(node.getAttribute('data-sparkline'))));
      });
    }
  },
  highcharts: {
    selector: '.highcharts-container',
    styles: './components/highcharts/highcharts.css',
    script: ['./components/base/jquery-3.4.1.min.js', './components/highcharts/highcharts.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        Highcharts.chart(node, parseJSON(node.getAttribute('data-highcharts-options')));
      });
    }
  },
  gmap: {
    selector: '.google-map',
    styles: './components/google-map/google-map.css',
    script: ['//maps.google.com/maps/api/js?key=AIzaSyBHij4b1Vyck1QAuGQmmyryBYVutjcuoRA&libraries=geometry,places&v=quarterly', './components/google-map/google-map.js'],
    init: function init(nodes) {
      var promises = [];
      nodes.forEach(function (node) {
        var sMap = new SimpleGoogleMap(_objectSpread({
          node: node,
          center: {
            lat: 0,
            lng: 0
          },
          zoom: 4
        }, parseJSON(node.getAttribute('data-settings'))));
        promises.push(new Promise(function (resolve) {
          sMap.map.addListener('tilesloaded', resolve);
        }));
      });
      return Promise.all(promises);
    }
  },
  gmapMarkerInfo: {
    selector: '[data-marker-info]',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        node.addEventListener('click', function () {
          var params = parseJSON(this.getAttribute('data-marker-info')),
              map = document.querySelector(params.mapId).simpleGoogleMap;
          map.showInfo(params.markerId);
        });
      });
    }
  },
  gmapFilter: {
    selector: '[data-map-filter]',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        node.addEventListener('click', function () {
          var params = parseJSON(this.getAttribute('data-map-filter')),
              map = document.querySelector(params.mapId).simpleGoogleMap;
          map.filter(params.tags);
        });
      });
    }
  },
  gmapSearch: {
    selector: '[data-map-search]',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        node.addEventListener('submit', function (event) {
          event.preventDefault();
          var input = this.querySelector('input'),
              map = document.querySelector(this.getAttribute('data-map-search')).simpleGoogleMap;
          map.search(input.value.trim());
        });
      });
    }
  },
  gmapPanorama: {
    selector: '[data-map-panorama]',
    dependencies: 'gmap',
    init: function init(nodes) {
      var promises = [];
      nodes.forEach(function (node) {
        var sMap = document.querySelector(node.getAttribute('data-map-panorama')).simpleGoogleMap,
            panorama = new google.maps.StreetViewPanorama(node, {
          position: sMap.params.center,
          pov: {
            heading: 34,
            pitch: 10
          }
        });
        sMap.map.setStreetView(panorama);
      });
      return Promise.all(promises);
    }
  },
  gmapNavigation: {
    selector: '[data-map-navigation]',
    dependencies: 'gmap',
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var map = document.querySelector(node.getAttribute('data-map-navigation')).simpleGoogleMap,
            prev = node.querySelector('[data-map-prev]'),
            next = node.querySelector('[data-map-next]'),
            title = node.querySelector('[data-map-title]'),
            currentId = '',
            setNavigation = function setNavigation(id) {
          map.map.setCenter(map.markers[id].position);
          title.innerHTML = map.markers[id].html;
          currentId = id;
        };

        setNavigation(Object.getOwnPropertyNames(map.markers)[0]);
        prev.addEventListener('click', function () {
          var list = Object.getOwnPropertyNames(map.markers),
              current = list.indexOf(currentId),
              next = current <= 0 ? list.length - 1 : current - 1;
          setNavigation(list[next]);
        });
        next.addEventListener('click', function () {
          var list = Object.getOwnPropertyNames(map.markers),
              current = list.indexOf(currentId),
              next = current >= list.length - 1 ? 0 : current + 1;
          setNavigation(list[next]);
        });
      });
    }
  },
  topbar: {
    selector: '.topbar',
    styles: './components/topbar/topbar.css'
  },
  multiswitch: {
    selector: '[data-multi-switch]',
    script: './components/multiswitch/multiswitch.js',
    dependencies: ['currentDevice', 'rdNavbar'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        MultiSwitch(_objectSpread({
          node: node,
          event: device.ios() ? 'touchstart' : 'click'
        }, parseJSON(node.getAttribute('data-multi-switch'))));
      });
    }
  },
  navigationSwitch: {
    selector: '[data-navigation-switch]',
    script: './components/multiswitch/multiswitch.js',
    dependencies: ['currentDevice', 'rdNavbar'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        var timeoutId,
            mSwitch,
            clickHandler = function clickHandler() {
          clearTimeout(timeoutId);
          this.multiSwitch.targets.forEach(function (target) {
            target.style.pointerEvents = 'none';
          });
          timeoutId = setTimeout(function () {
            window.dispatchEvent(new Event('resize'));
            this.multiSwitch.targets.forEach(function (target) {
              target.style.pointerEvents = 'auto';
            });
          }.bind(this), 300);
        },
            resizeHandler = function resizeHandler() {
          if (window.matchMedia('(max-width: 1199px)').matches) {
            mSwitch.removeHandlers();
            mSwitch.scope = document.querySelectorAll('.rd-navbar-sidebar, [data-navigation-switch]');
            mSwitch.assignHandlers();
          } else {
            mSwitch.removeHandlers();
            mSwitch.scope = null;
            mSwitch.assignHandlers();
          }
        };

        if (window.matchMedia('(max-width: 1199px)').matches) {
          mSwitch = MultiSwitch({
            node: node,
            event: device.ios() ? 'touchstart' : 'click',
            targets: 'html',
            "class": 'rd-navbar-sidebar-active',
            scope: '.rd-navbar-sidebar, [data-navigation-switch]'
          });
        } else {
          mSwitch = MultiSwitch({
            node: node,
            event: device.ios() ? 'touchstart' : 'click',
            targets: 'html',
            "class": 'rd-navbar-sidebar-active',
            state: true
          });
        }

        window.addEventListener('resize', resizeHandler);
        node.addEventListener('click', clickHandler);
      });
    }
  },
  jumbotron: {
    selector: '.jumbotron',
    styles: './components/jumbotron/jumbotron.css'
  },
  iconBox: {
    selector: '.icon-box',
    styles: './components/icon-box/icon-box.css'
  },
  dockModal: {
    selector: '[data-dockmodal-button]',
    styles: ['./components/font-awesome/font-awesome.css', './components/dockmodal/dockmodal.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/dockmodal/dockmodal.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        node.addEventListener('click', function () {
          $(node.getAttribute('data-dockmodal-button')).dockmodal({
            initialState: 'minimized',
            minimizedWidth: 220,
            height: 430,
            title: function title() {
              return document.querySelector(node.getAttribute('data-dockmodal-button')).getAttribute('data-dockmodal-title');
            }
          });
        });
      });
    }
  },
  widgetCounter: {
    selector: '.widget-counter',
    styles: './components/widget-counter/widget-counter.css'
  },
  scroller: {
    selector: '.scroller',
    styles: './components/scroller/scroller.css'
  },
  user: {
    selector: '.user',
    styles: './components/user/user.css'
  },
  jquerySortable: {
    selector: '.sortable',
    styles: ['./components/font-awesome/font-awesome.css', './components/jquery-sortable/jquery-sortable.css'],
    script: ['./components/base/jquery-3.4.1.min.js', './components/base/jquery-ui.min.js'],
    init: function init(nodes) {
      nodes.forEach(function (node) {
        $(node).sortable({
          connectWith: node.getAttribute('data-connect-group'),
          axis: 'y',
          handle: '.sortable-drag',
          update: function update(event, ui) {
            var Item = ui.item;
            var ParentList = Item.parent(); // If item is already checked move it to "current items list"

            if (ParentList.hasClass('sortable-current')) {
              Item.find('input[type="checkbox"]').prop('checked', false);
            }

            if (ParentList.hasClass('sortable-completed')) {
              Item.find('input[type="checkbox"]').prop('checked', true);
            }
          }
        });
      }); // Custom Functions to handle/assign list filter behavior

      $('.sortable-drag').on('click', function (event) {
        event.preventDefault();

        if ($(event.target).parents('.sortable-completed').length) {
          $(this).parent().remove();
        }
      });
    }
  },
  stateface: {
    selector: '.stateface',
    styles: './components/stateface/stateface.css'
  },
  footer: {
    selector: '.footer',
    styles: './components/footer/footer.css'
  },
  toTop: {
    selector: '.to-top',
    script: './components/base/jquery-3.4.1.min.js',
    dependencies: 'currentDevice',
    init: function init(nodes) {
      $(nodes).on('mousedown', function () {
        this.classList.add('active');
        $('html, body').stop().animate({
          scrollTop: 0
        }, 500, 'swing', function () {
          this.classList.remove('active');
        }.bind(this));
      });
    }
  }
};
/**
 * Wrapper to eliminate json errors
 * @param {string} str - JSON string
 * @returns {object} - parsed or empty object
 */

function parseJSON(str) {
  try {
    if (str) return JSON.parse(str);else return {};
  } catch (error) {
    return {};
  }
}
/**
 * Component readiness check
 * @param {string} name
 * @return {Promise}
 */


function checkComponent(name) {
  return new Promise(function (resolve) {
    var component = window.components[name];

    if (!component || component.state === 'absent') {
      resolve();
    } else {
      if (component.state === 'ready') {
        resolve();
      } else {
        window.addEventListener("".concat(name, ":readyScripts"), resolve);
      }
    }
  });
}
/**
 * @param {Array} params - parameters array
 * @param {function} cb - function that returns a promise
 * @returns {Promise}
 */


function makeAsync(params, cb) {
  var inclusions = [];
  params.forEach(function (path) {
    inclusions.push(cb(path));
  });
  return Promise.all(inclusions);
}
/**
 * @param {Array} params - parameters array
 * @param {function} cb - function that returns a promise
 * @returns {Promise}
 */


function makeSync(params, cb) {
  var chain = Promise.resolve();
  params.forEach(function (path) {
    chain = chain.then(cb.bind(null, path));
  });
  return chain;
}
/**
 * Adds a link tag to the page.
 * @param {string} path - path to styles file
 */


function includeStyles(path) {
  return new Promise(function (resolve) {
    if (document.querySelector("link[href=\"".concat(path, "\"]"))) {
      resolve();
    } else {
      var link = document.createElement('link');
      link.setAttribute('rel', 'stylesheet');
      link.setAttribute('href', path);
      link.addEventListener('load', resolve);
      document.querySelector('head').appendChild(link);
    }
  });
}
/**
 * Adds a script tag to the page.
 * @param {Array|string} path - path to script file
 * @return {Promise}
 */


function includeScript(path) {
  return new Promise(function (resolve) {
    var node = document.querySelector("script[src=\"".concat(path, "\"]"));

    if (node) {
      if (node.getAttribute('data-loaded') === 'true') {
        resolve();
      } else {
        node.addEventListener('load', resolve);
      }
    } else {
      var script = document.createElement('script');
      script.src = path;
      script.addEventListener('load', function () {
        script.setAttribute('data-loaded', 'true');
        resolve();
      });
      document.querySelector('head').appendChild(script);
    }
  });
}
/**
 * @param {object} component
 */


function initComponent(component) {
  var stylesState = Promise.resolve(),
      scriptsState = Promise.resolve();
  component.state = 'load';

  if (component.styles) {
    stylesState = stylesState.then(makeAsync.bind(null, component.styles, includeStyles));
  }

  if (component.script) {
    scriptsState = scriptsState.then(makeSync.bind(null, component.script, includeScript));
  }

  if (component.dependencies) {
    scriptsState = scriptsState.then(makeSync.bind(null, component.dependencies, checkComponent));
  }

  if (component.init) {
    scriptsState = scriptsState.then(component.init.bind(null, component.nodes));
  }

  stylesState.then(function () {
    window.dispatchEvent(new Event("".concat(component.name, ":readyStyles")));
  });
  scriptsState.then(function () {
    window.dispatchEvent(new Event("".concat(component.name, ":readyScripts")));
    component.state = 'ready';
  });
  return {
    scriptsState: scriptsState,
    stylesState: stylesState
  };
}
/**
 * @param {object} components
 */


function initComponents(components) {
  var stylesPromises = [],
      scriptsPromises = [];
  if (!window.components) window.components = components; // Components preparation

  for (var key in components) {
    var component = components[key];
    component.name = key;
    component.nodes = document.querySelectorAll(component.selector);

    if (component.styles && !(component.styles instanceof Array)) {
      component.styles = [component.styles];
    }

    if (component.script && !(component.script instanceof Array)) {
      component.script = [component.script];
    }

    if (component.dependencies && !(component.dependencies instanceof Array)) {
      component.dependencies = [component.dependencies];
    }

    if (component.nodes.length) {
      component.state = 'pending';
    } else {
      component.state = 'absent';
    }
  }

  for (var _key in components) {
    var _component = components[_key];

    if (_component.state === 'pending') {
      var componentPromises = initComponent(_component);
      stylesPromises.push(componentPromises.stylesState);
      scriptsPromises.push(componentPromises.scriptsState);
    }
  }

  Promise.all(scriptsPromises).then(function () {
    window.dispatchEvent(new Event('components:ready'));
  });
  Promise.all(stylesPromises).then(function () {
    window.dispatchEvent(new Event('components:stylesReady'));
  });
} // Main


window.addEventListener('load', function () {
  initComponents(components);
});

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!**************************************************************************!*\
  !*** multi ./public/components/base/script.js ./resources/sass/app.scss ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /var/www/trcker-merchant-portal/Frontend/trckr/public/components/base/script.js */"./public/components/base/script.js");
module.exports = __webpack_require__(/*! /var/www/trcker-merchant-portal/Frontend/trckr/resources/sass/app.scss */"./resources/sass/app.scss");


/***/ })

/******/ });