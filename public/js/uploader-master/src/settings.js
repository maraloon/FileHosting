//-- Some functions to work with our UI

function add_log(message)
{
  var template = '<li>[' + new Date().getTime() + '] - ' + message + '</li>';
  
  $('#debug').find('ul').prepend(template);
}

function add_file(id, file)
{
  var template = '' +
    '<div class="file" id="uploadFile' + id + '">' +
      '<div class="info">' +
        '#1 - <span class="filename" title="Size: ' + file.size + 'bytes - Mimetype: ' + file.type + '">' + file.name + '</span><br /><small>Status: <span class="status">Waiting</span></small>' +
      '</div>' +
      '<div class="bar">' +
        '<div class="progress" style="width:0%"></div>' +
      '</div>' +
    '</div>';
    
    $('#fileList').prepend(template);
}

function update_file_status(id, status, message)
{
  $('#uploadFile' + id).find('span.status').html(message).addClass(status);
}

function update_file_progress(id, percent)
{
  $('#uploadFile' + id).find('div.progress').width(percent);
}

// Upload Plugin itself
$('#drag-and-drop-zone').dmUploader({
  url: '/upload',
  dataType: 'json',
  allowedTypes: '*',
  maxFiles:0,
  onInit: function(){
    add_log('Penguin initialized :)');
  },
  onBeforeUpload: function(id){
    add_log('Starting the upload of #' + id);
    
    update_file_status(id, 'uploading', 'Uploading...');
  },
  onNewFile: function(id, file){
    add_log('New file added to queue #' + id);
    
    add_file(id, file);
  },
  onComplete: function(){
    add_log('All pending tranfers finished');
    
  },
  onUploadProgress: function(id, percent){
    var percentStr = percent + '%';

    update_file_progress(id, percentStr);
  },
  onUploadSuccess: function(id, data){
    if (JSON.stringify(data)=='true') {
      add_log('Upload of file #' + id + ' completed');
      add_log('Server Response for file #' + id + ': ' + 'Upload Successed');
      update_file_status(id, 'success', 'Upload Complete');
      update_file_progress(id, '100%');
      window.location.replace('/add_info');
    }
    else{
      add_log('Server Response for file #' + id + ': ' + 'Upload Failed');
      update_file_status(id, 'error', 'Upload Failed. Unknown reason');
    }

  },
  onUploadError: function(id, message){
    add_log('Failed to Upload file #' + id + ': ' + message);
    
    update_file_status(id, 'error', message);
  },
  onFileTypeError: function(file){
    add_log('File \'' + file.name + '\' cannot be added: must be an image');
    
  },
  onFileSizeError: function(file){
    add_log('File \'' + file.name + '\' cannot be added: size excess limit');
  },
  onFallbackMode: function(message){
    alert('Browser not supported(do something else here!): ' + message);
  }
});