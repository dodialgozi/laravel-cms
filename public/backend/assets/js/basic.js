/**
 * url => String;
 * module => String;
 * name => String;
 **/
function hapus(url, module, name){
  const message = `${module ? module : 'data'}${name ? ` <b>${name}</b>` : ''}`;
  swal.fire({
    title: 'Anda Yakin?',
    html: `Yakin akan menghapus ${message}?`,
    icon: 'warning',
    iconColor: '#f46a6a',
    showCancelButton: true,
    confirmButtonColor: '#f46a6a',
    cancelButtonColor: '#74788d',
    confirmButtonText: 'Hapus',
    cancelButtonText: 'Batal',
  }).then(function (result) {
    if(result.isConfirmed){
      $.ajax({
        url: url,
        method: 'DELETE',
        data: {'message': message},
      }).done(data => {
        if(data.success) {
          swal.fire({
            icon: 'success',
            title: 'Terhapus!',
            html: `Berhasil menghapus ${message}`,
            showConfirmButton: false,
          });
          setTimeout(() => {
            location.reload();
          }, 800);
        } else {
          swal.fire('Maaf', data.message, 'error');
        }
      }).fail(xhr => {
        swal.fire('Maaf', xhr.responseJSON.message ?? 'Terjadi kesalahan saat menghapus data.', 'error');
        if(xhr.responseJSON.errorMessage) console.error(xhr.responseJSON.errorMessage);
        else console.error(xhr);
      });
    } 
  });
}
/**
 * url => String;
 * ids => Array; list of encoded ids
 * module => String;
 * name => Array;
 **/
function hapusSemua(url, ids, module, names){
  swal.fire({
    title: 'Anda Yakin?',
    html: `Yakin akan menghapus ${module ? module.toLowerCase() : 'data'} yang ditandai berikut?${names ? ` <br/><b>${names.join('<br/>')}</b>` : ''}`,
    icon: 'warning',
    iconColor: '#f46a6a',
    showCancelButton: true,
    confirmButtonColor: '#f46a6a',
    cancelButtonColor: '#74788d',
    confirmButtonText: 'Hapus Ditandai',
    cancelButtonText: 'Batal',
  }).then(function (result) {
    if(result.isConfirmed){
      $.ajax({
        url: url,
        method: 'DELETE',
        data: {
          ids: ids,
          names: names
        }
      }).done(data => {
        if(data.success) {
          swal.fire({
            icon: 'success',
            title: 'Terhapus!',
            text: `Berhasil menghapus ${module ?? 'data'} yang ditandai.`,
            showConfirmButton: false,
          });
          setTimeout(() => {
            location.reload();
          }, 800);
        } else {
          swal.fire('Maaf', data.message, 'error');
        }
      }).fail(xhr => {
        swal.fire('Maaf', xhr.responseJSON.message ?? 'Terjadi kesalahan saat menghapus data.', 'error');
        if(xhr.responseJSON.errorMessage) console.error(xhr.responseJSON.errorMessage);
        else console.error(xhr);
      });
    } 
  });
}

/**
 * url => String;
 * method => String; GET, POST, PUT, DELETE
 * title => String;
 * body => String; Text/Html
 * icon => String; success, error, warning, info, question
 * iconColor => String; css color
 * showCancelButton => Boolean;
 * confirmButtonColor => String; css color
 * cancelButtonColor => String; css color
 * confirmButtonText => String;
 * cancelButtonText => String;
 * messageSuccess => String; Text/Html
 **/
 function proses(url, method, {title, body, icon = 'info', iconColor = '#556ee6', showCancelButton, confirmButtonColor = '#556ee6', cancelButtonColor = '#74788d', confirmButtonText = 'Proses', cancelButtonText = 'Batal', messageSuccess, data, input, inputPlaceholder, didOpen}) {
  swal.fire({
    title: title,
    html: body,
    icon: icon,
    iconColor: iconColor,
    showCancelButton: showCancelButton ?? true,
    confirmButtonColor: confirmButtonColor,
    cancelButtonColor: cancelButtonColor,
    confirmButtonText: confirmButtonText,
    cancelButtonText: cancelButtonText,
    input: input,
    inputPlaceholder: input ? (inputPlaceholder ?? '') : inputPlaceholder,
    didOpen: didOpen,
  }).then(function (result) {
    if(result.isConfirmed){
      $.ajax({
        url: url,
        method: method,
        data: {data: data, input: input ? result.value : null},
      }).done(data => {
        if(data.success) {
          swal.fire({
            icon: 'success',
            title: 'Berhasil',
            html: messageSuccess ?? data.message ?? 'Sukses.',
            showConfirmButton: false,
          });
          setTimeout(() => {
            location.reload();
          }, 800);
        } else {
          swal.fire('Maaf', data.message, 'error');
        }
      }).fail(xhr => {
        swal.fire('Maaf', xhr.responseJSON.message ?? 'Terjadi kesalahan.', 'error');
        if(xhr.responseJSON.errorMessage) console.error(xhr.responseJSON.errorMessage);
        else console.error(xhr);
      });
    } 
  });
}

/**
 * callbackPosition => Function(position); callback
 * callbackError => Function(); callback
 **/
function getLocation(callbackPosition, callbackError) {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      callbackPosition,
      function errorCallback(error) {
        callbackError();
        console.error(error);
      },
      {
        maximumAge:Infinity,
        timeout:5000
      }
    );
  } else {
    callbackError();
    console.error('Tidak support gps.');
  }
}

/**
 * selector => String;
 * config => [
 *      url => String; required
 *      key => String; required if not using objectValue
 *      value => String;
 *      objectValue => Function(obj);
 *      placeholder => String;
 *      disableSearch => Boolean;
 *      allowClear => Boolean;
 *      closeOnSelect => Boolean;
 *      tags => Boolean;
 * ]
 **/
function loadSelect2PerPage(selector, {url, key, value, objectValue, placeholder, hashId = false, disableSearch = false, allowClear = false, closeOnSelect = true, tags = false}) {
  $(selector).select2({
    ajax: {
      url: url,
      dataType: 'JSON',
      delay: 250,
      data: function (params) {
        return {
          q: params.term,
          page: params.page,
          hash_id: hashId ? key : null
        };
      },
      processResults: function(data, params) {
        data = data.data;
        return {
          results: $.map(data.data, objectValue ?? function(obj) {
            return {
              id: obj[key],
              text: value != null ? obj[value] : obj[key],
            };
          }),
          pagination: {
            more: ((params.page || 1) * data.per_page) < data.total
          }
        };
      },
      cache: false
    },
    placeholder: placeholder ?? null,
    dropdownParent: $(selector).parent(),
    minimumResultsForSearch: disableSearch ? -1 : 1,
    allowClear: allowClear ?? false,
    tags: tags ?? false,
    closeOnSelect: closeOnSelect ?? false,
  });
}

/**
 * selector => String;
 * config => [
 *      url => String; required
 *      key => String; required if not using objectValue
 *      value => String;
 *      objectValue => Function(obj);
 *      placeholder => String;
 *      disableSearch => Boolean;
 *      allowClear => Boolean;
 *      closeOnSelect => Boolean;
 *      tags => Boolean;
 * ]
 **/
function loadSelect2All(selector, {url, key, value, objectValue, placeholder, hashId = false, disableSearch = false, allowClear = false, closeOnSelect = true, tags = false}) {
  $(selector).select2({
    ajax: {
      url: url,
      dataType: 'JSON',
      delay: 250,
      data: function (params) {
        return {
          q: params.term,
          hash_id: hashId ? key : null
        };
      },
      processResults: function(data, params) {
        return {
          results: $.map(data.data, objectValue ?? function(obj) {
            return {
              id: obj[key],
              text: value != null ? obj[value] : obj[key],
            };
          })
        };
      },
      cache: false
    },
    placeholder: placeholder ?? null,
    dropdownParent: $(selector).parent(),
    minimumResultsForSearch: disableSearch ? -1 : 1,
    allowClear: allowClear ?? false,
    tags: tags ?? false,
    closeOnSelect: closeOnSelect ?? true,
  });
}

// get kinda unique mobile device
function mobileDeviceId() {
  return btoa(JSON.stringify({
    'width': window.screen.width,
    'height': window.screen.height,
    'pixelRatio': window.devicePixelRatio,
  })).hashCode();
}

// JSON from form
function formToJSON(f) {
  var fd = $(f).serializeArray();
  var d = {};
  $(fd).each(function() {
      if (d[this.name] !== undefined){
          if (!Array.isArray(d[this.name])) {
              d[this.name] = [d[this.name]];
          }
          d[this.name].push(this.value);
      }else{
          d[this.name] = this.value;
      }
  });
  return d;
}

// Random String Generator
function randomGen(length) {
  let result = '';
  const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  for (let i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * characters.length));
  }
  return result;
}
function randomGen2(length) {
  let result = '';
  const characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  for (let i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * characters.length));
  }
  return result;
}
function randomGenAlpha(length) {
  let result = '';
  const characters = 'abcdefghijklmnopqrstuvwxyz';
  for (let i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * characters.length));
  }
  return result;
}


// Extensions
Number.prototype.rupiah = function ({digitKoma = 0, prefix = '', suffix = ''} = {}) {
  return prefix + (new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: digitKoma,
    maximumFractionDigits: digitKoma,
  }).format(this).replace(/Rp\s/, '')) + suffix;
}

String.prototype.goDriveId = function () {
  let id = (/.+file\/d\/(?<id>[a-zA-Z0-9\_\-]+).*\/.*/).exec(this)?.groups['id'] ?? null;
  if(id != null) return id;
  id = (/.+uc\?id=(?<id>[a-zA-Z0-9\_\-]+).*/).exec(this)?.groups['id'] ?? null;
  if(id != null) return id;
  return id;
}

Number.prototype.padStart = function(length = 0, pad = '0') {
    return String(this).padStart(length, pad);
}

String.prototype.under2Space = function () {
  return this.replace(/_/g, " ");
}

String.prototype.ucwords = function () {
  // not supported regex in safari
  return this.toLowerCase()
    .split(' ')
    .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
    .join(' ');
  // return this.toLowerCase().replace(/(?<= )[^\s]|^./g, a => a.toUpperCase());
}

String.prototype.hashCode = function(seed = 0) {
  let h1 = 0xdeadbeef ^ seed,
    h2 = 0x41c6ce57 ^ seed;
  for (let i = 0, ch; i < this.length; i++) {
    ch = this.charCodeAt(i);
    h1 = Math.imul(h1 ^ ch, 2654435761);
    h2 = Math.imul(h2 ^ ch, 1597334677);
  }
  
  h1 = Math.imul(h1 ^ (h1 >>> 16), 2246822507) ^ Math.imul(h2 ^ (h2 >>> 13), 3266489909);
  h2 = Math.imul(h2 ^ (h2 >>> 16), 2246822507) ^ Math.imul(h1 ^ (h1 >>> 13), 3266489909);
  
  return 4294967296 * (2097151 & h2) + (h1 >>> 0);
}

Array.prototype.equal = function(array) {
    return Array.isArray(array) &&
        this.length === array.length &&
        this.every((val, idx) => val === array[idx]);
}

// Others
function showLoading(){
  $('#preloader').show();
  $('#status').show();
}

function hideLoading(){
  $('#status').fadeOut();
  $('#preloader').delay(preloaderDelay).fadeOut('slow');
}

function saveModal(selector){
  var formModal = $(selector);
  formModal.submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: formModal.attr('method'),
      url: formModal.attr('action'),
      data: formModal.serialize(),
      success: function (data) {
        formModal.parent().modal('hide');
        showLoading();
        $(':input', selector)
          .not(':button, :submit, :reset, :hidden')
          .val('')
          .prop('checked', false)
          .prop('selected', false);
        hideLoading();
      },
      error: function (data) {
        showLoading();
        hideLoading();
      },
    });
  });
}

// load select2
function loadSelect2(selector = '.select2') {
  $(selector).each(function() {
    $(this).select2({
      placeholder: $(this).attr('placeholder') ?? 'Pilih',
      dropdownParent: $(this).parent(),
      minimumResultsForSearch: $(this).attr('disable-search') === 'true' ? -1 : 1,
      allowClear: $(this).attr('allow-clear') === 'true',
      tags: $(this).attr('tags') !== 'true',
      closeOnSelect: $(this).attr('close-on-select') !== 'false',
    });
  });
}

// load uang
function loadUang(selector = 'input.uang') {
  $(selector).each(function() {
    if($(this).val() != '') {
      $(this).val(($(this).val()+'').replace('.', ','));
    }
  
    const digitKoma = $(this).attr('uang-koma') ? parseInt($(this).attr('uang-koma')) : 0;
    const asString = $(this).attr('uang-string') == 'true';
    $(this).inputmask({alias: 'decimal', groupSeparator: '.', autoGroup: true, radixPoint: ',', digits: digitKoma, digitsOptional: true, rightAlign: false, placeholder: '', removeMaskOnSubmit: true, autoUnmask: true, unmaskAsNumber: !asString});
  });
}

// load parsley validation
function loadParsleyValidation(selector) {
  $(selector).parsley({
		errorsContainer: function (field) {
			const formGroup = field.$element.closest('.form-group');
			return formGroup.length ? formGroup : field.$element.parent();
		},
		classHandler: function (field) {
			const formGroup = field.$element.closest('.form-group');
			const container = formGroup.length ? formGroup : field.$element.parent();

			if(field.$element.hasClass('chosen')) {
				return container.find('div.chosen-container');
			}

			return field.$element;
		}
	});
}

// document ready
$(function() {
  $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });
  
  $(document).on('click', 'a[href="#"]', function(e) {
    e.preventDefault();
  });

	window.Parsley.on('form:error', function() {
		const firstError = this.$element.find('.parsley-error').first();
		if(firstError.next().hasClass('select2-container')) {
      firstError.select2('open');
		}
	});
  
  
  $(document).on('click', 'a.doc-preview', function(e) {
    e.preventDefault();
    let url = $(this).prop('href');

    const goDriveId = url.goDriveId();
    if(goDriveId != null) {
      const $modalDocPreview = $('#modal-doc-preview');
      $modalDocPreview.find('.modal-body').html(`<div class="modal-body"><iframe src="https://drive.google.com/file/d/${goDriveId}/preview" width="100%" height="100%" style="min-height: 650px;" frameborder="0"></iframe></div>`);
      $modalDocPreview.modal('show');
      return;
    }

    if(url.match(/\.(jpeg|jpg|gif|png)$/) != null) {
      const $modalImagePreview = $('#modal-image-preview');
      $modalImagePreview.find('.modal-content').html(`<a href="${url}" class="btn btn-dark waves-effect" target="_blank" title="Pop-out"><i class="mdi mdi-open-in-new mdi-24px"></i></a><img src="${url}" />`);
      $modalImagePreview.modal('show');
      return;
    }
    
    window.open(url);
  });

  loadSelect2();

  loadUang();

  $(document).on('click', '.swal,[swal]', function(e) {
    e.preventDefault();
    let icon = $(this).attr('swal-icon') ?? 'info';
    let title = $(this).attr('swal-title') ?? '';
    let message = $(this).attr('swal-message') ?? '';
    let time = $(this).attr('swal-time');
    swal.fire(title, message, icon);
    if(time > 0) {
      setTimeout(() => {
          swal.close();
      }, time);
    }
  });
});