@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Equipment Fields') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Category') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('All Category') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Equipment Fields') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Equipment Fields') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('admin.equipment_management.categories', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <div class="alert alert-danger pb-1 dis-none" id="equipmentErrors">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>

              <form id="equipmentForm"
                action="{{ route('admin.equipment_management.update_equipment_field', ['id' => $category->id]) }}"
                enctype="multipart/form-data" method="POST">
                @csrf

                <div class="row">
                  <div class="col-lg-12">
                
                    <div class="agcd-equipment-fields agcd-repeater">
                        <?php if( !empty($equipment_fields) ){ ?>
                          <?php foreach($equipment_fields as $field){ ?>
                            
                            <div class="agcd-repeater-item">
                              <span class="agcd-remove-item">Remove</span>
                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Field Type</label>
                                          <select required data-group="equipment_fields" data-name="type" class="form-control">
                                              <option value="Text" <?php echo ($field['type'] == 'Text')?'selected':''; ?>>Text</option>
                                              <option value="Dropdown" <?php echo ($field['type'] == 'Dropdown')?'selected':''; ?>>Dropdown</option>
                                              <option value="Price" <?php echo ($field['type'] == 'Price')?'selected':''; ?>>Price</option>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Name</label>
                                          <input required type="text" value="<?php echo $field['name']; ?>" data-group="equipment_fields" data-name="name" class="form-control">
                                          
                                      </div>
                                  </div>
                                  <div class="col-md-12 for-dropdown" style="display:<?php echo ($field['type'] == 'Dropdown')?'':'none'; ?>">
                                      <div class="form-group">
                                          <label>Options</label>
                                          <p>Enter Comma (,) Separated Values</p>
                                          <textarea data-group="equipment_fields" data-name="options" class="form-control"><?php echo $field['options']; ?></textarea>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          
                          <?php } ?>
                        <?php } ?>
                    </div>

                    <div class="agcd-repeater-dummy" style="display:none;">
                        <div class="agcd-repeater-item">
                            <span class="agcd-remove-item">Remove</span>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Field Type</label>
                                        <select data-group="equipment_fields" data-name="type" class="form-control">
                                            <option value="Text">Text</option>
                                            <option value="Dropdown">Dropdown</option>
                                            <option value="Price">Price</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" data-group="equipment_fields" data-name="name" class="form-control">
                                        
                                    </div>
                                </div>
                                <div class="col-md-12 for-dropdown" style="display:none">
                                    <div class="form-group">
                                        <label>Options</label>
                                        <p>Enter Comma (,) Separated Values</p>
                                        <textarea data-group="equipment_fields" data-name="options" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>

                  
                </div>

              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="equipmentForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript" src="{{ asset('assets/js/admin-partial.js') }}"></script>

  <script>
    //agcd-repeater
    $(document).ready(function(){
        
        $('.agcd-repeater').after('<button type="button" class="btn btn-primary btn-sm agcd-add-repeater-item">Add Fields</button>');
        agcd_reset_repeater_keys();
        $(document).on('click', '.agcd-add-repeater-item', function(){
            var repeater_item = $('.agcd-repeater-dummy').html();
            $('.agcd-repeater').append(repeater_item);
            agcd_reset_repeater_keys();
        });

        $(document).on('click','.agcd-remove-item', function(){
            $(this).parent().remove();
            agcd_reset_repeater_keys();
        });

        $(document).on('change','select[data-group="equipment_fields"][data-name="type"]', function(){
          var type_ = $(this).val();

          var this_item = $(this).parent().parent().parent().parent();
          this_item.find('.for-dropdown').find('textarea').val('');
          this_item.find('.for-dropdown').find('textarea').prop('required', false);
          this_item.find('.for-dropdown').hide();
          if(type_ == 'Dropdown'){
            this_item.find('.for-dropdown').show();
          }
        });

        function agcd_reset_repeater_keys(){
            var i = 0;
            $('.agcd-equipment-fields .agcd-repeater-item').each(function(){
                $(this).find('input, select').each(function(){
                    $(this).attr('name',$(this).attr('data-group')+'['+i+']['+$(this).attr('data-name')+']');
                    $(this).prop('required',true);
                });

                $(this).find('textarea').each(function(){
                    $(this).attr('name',$(this).attr('data-group')+'['+i+']['+$(this).attr('data-name')+']');
                    
                });

                i++;
            });
        }
    });
 
  </script>
@endsection
