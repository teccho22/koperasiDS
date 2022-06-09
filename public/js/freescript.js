(function() {

	$.fn.bootstrapTable.defaults.classes = 'table';
	$.fn.bootstrapTable.defaults.contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
	$.fn.bootstrapTable.defaults.method = 'POST';
	$.fn.bootstrapTable.defaults.sidePagination = 'server';
	$.fn.bootstrapTable.defaults.pagination = true;
	$.fn.bootstrapTable.defaults.buttonsClass = 'default btn-sm';
	$.fn.bootstrapTable.defaults.pageSize = 50;
	$.fn.bootstrapTable.defaults.pageList = [10, 25, 50, 75, 100];

	var _temp;

	function _makeOverlay()
	{
		if (!$('#__overlay__').length)
		{
			var _temp = '\
				<div class="overlay" id="__overlay__"></div>\
			';
			$('body').append(_temp);
		}
	}

	function _clear(that)
	{
		$(that).remove();

		if ($('.__panel__').length == 0)
		{
			$('#__overlay__').remove();
		}
	}

	$.clearActivePanel = function(param)
	{
		var activePanel = $('.__confirm__').last();
		_clear(activePanel);
	}

	$.clearAlert = function(param)
	{
		var activePanel = $('.__alert__').last();
		_clear(activePanel);
	}

	$.confirm = function(param)
	{
		_makeOverlay();
		var opts = $.extend({
			// heading: '<i class="fa fa-exclamation-triangle" style="color: #575757;"> <span>Please Confirm</span></i>',
			heading: '<span>Please Confirm</span>',
			text: 'Are you sure ?',
			no : 'No',
			yes : 'Yes',
			useCancel : false,
			buttonAlignClass: 'text-right',
			onYes: function() {return undefined;},
			onNo: function() {return undefined;},
			onCancel: function() {return undefined;},
			clearActivePanelOnYes : true,
		}, param || {});

		var btnCancel = '';
		if (opts.useCancel == true) 
		{
			btnCancel = '<button class="btn btn-danger __cancel__" type="button"><i class="fa fa-times"></i>&nbsp;Cancel</button>';
		}

		_temp = '';
		_temp += '\
			<div class="__confirm__ __panel__ panel">\
				<div class="panel-heading bold-header">'+ opts.heading +'</div>\
					<div class="panel-body">'+ opts.text +'</div>\
					<div class="panel-footer text-right">\
						'+ btnCancel +'\
						<button class="btn btn-default __no__" type="button"><i class="fa fa-times"></i>&nbsp;'+ opts.no +'</button>\
						<button class="btn btn-primary __yes__" type="button"><i class="fa fa-check"></i>&nbsp;'+ opts.yes +'</button>\
					</div>\
				</div>\
			</div>\
		';

		$('body').append(_temp);

		var activePanel = $('.__confirm__').last();
		var noButton = $(activePanel).find('.__no__');
		var yesButton = $(activePanel).find('.__yes__');
		var cancelButton = $(activePanel).find('.__cancel__');
		$(yesButton).focus();

		$(noButton).click(function()
		{
			_clear(activePanel);
			opts.onNo();
		});
		$(yesButton).click(function()
		{
			if(opts.clearActivePanelOnYes)
			{
				_clear(activePanel);
			}
			
			opts.onYes();
		});
		$(cancelButton).click(function()
		{
			_clear(activePanel);
			opts.onCancel();
		});
	};

	$.confirmReason = function(param)
	{
		_makeOverlay();
		var opts = $.extend({
			// heading: '<i class="fa fa-exclamation-triangle" style="color: #575757;"> <span>Please Confirm</span></i>',
			heading: '<span>Please Confirm</span>',
			text: 'Are you sure ?',
			no : 'Cancel',
			yes : 'Continue',
			buttonAlignClass: 'text-right',
			onYes: function(reason) {return reason} ,
			onNo: function() {return undefined;},
			clearActivePanelOnYes : true,
			label :'Reason'
		}, param || {});

		_temp = '';
		_temp += '\
			<div class="__confirm__ __panel__ panel">\
				<div class="panel-heading bold-header">'+ opts.heading +'</div>\
					<div class="panel-body">\
						<div class="col-12" style="white-space: pre-line">'+ opts.text +'</div>\
						<div class="col-12">\
							<div class="form-group row required" style="margin-bottom :0; margin-top:15px">\
								<label class="col-sm-4">'+opts.label+'</label>\
								<div class="col-sm-8">\
									<input type="text" class="form-control input-xsm" id="inputConfirmReason" maxlength="50">\
								</div>\
							</div>\
						</div>\
					</div>\
					<div class="panel-footer text-right">\
						<button class="btn btn-default __no__" type="button"><i class="fa fa-times"></i>&nbsp;'+ opts.no +'</button>\
						<button class="btn btn-primary __yes__" type="button"><i class="fa fa-check"></i>&nbsp;'+ opts.yes +'</button>\
					</div>\
				</div>\
			</div>\
		';

		$('body').append(_temp);

		var activePanel = $('.__confirm__').last();
		var noButton = $(activePanel).find('.__no__');
		var yesButton = $(activePanel).find('.__yes__');
		$(yesButton).focus();

		$(noButton).click(function()
		{
			_clear(activePanel);
			opts.onNo();
		});
		$(yesButton).click(function()
		{
			if(opts.clearActivePanelOnYes)
			{
				if($('#inputConfirmReason').val() == '')
				{
					$.inputError({
						inputId: '#inputConfirmReason',
						message: 'Can\'t be blank.'
					});
				}
				else
				{
					var reason = $('#inputConfirmReason').val();
					opts.onYes(reason);
					_clear(activePanel);
				}
			}
		});
	};

	$.alert = function(param)
	{
		_makeOverlay();
		var opts = $.extend({
			heading: 'Alert',
			text: '',
			close: 'Close',
			buttonAlignClass: 'text-right',
			closeOnEscape: true,
			onClose: function() {}
		}, param || {});

		_temp = '\
			<div class="__alert__ __panel__ panel panel-default">\
				<div class="panel-heading">'+ opts.heading +'</div>\
					<div class="panel-body">'+ opts.text +'</div>\
					<div class="panel-footer text-right">\
						<button class="btn btn-default __close__"><i class="fa fa-times"></i> '+ opts.close +'</button>\
					</div>\
				</div>\
			</div>\
		';

		$('body').append(_temp);

		var activePanel = $('.__alert__').last();
		var closeButton = $(activePanel).find('.__close__');
		$(closeButton).focus();

		$(closeButton).click(function()
		{
			_clear(activePanel);
			opts.onClose();
		});

		if (opts.closeOnEscape == true)
		{
			$(document).keypress(function(e) {
				if (e.keyCode == 27)
				{
					closeButton.click();
				}
			});
		}
	}

	$.loading = function(param)
	{
		var opts = $.extend({
			text: 'Loading'
		}, param || {});

		if(_theme.indexOf('dark')!=-1){
			_temp = '\
				<div class="overlay" id="__overlay_load__"></div>\
				<div class="loading-container" id="__loader__">\
					<div class="loading-wrapper">\
				        <div class="loading-circle"></div>\
				        <div class="loading-circle"></div>\
				        <div class="loading-circle"></div>\
				        <div class="loading-shadow"></div>\
				        <div class="loading-shadow"></div>\
				        <div class="loading-shadow"></div>\
				    </div>\
				</div>\
			';
		}
		else
		{
			_temp = '\
				<div class="overlay" id="__overlay_load__"></div>\
				<div class=mt-5 id="__loader__">\
					<div class="progress">\
						<div class="progress-bar progress-bar-striped active w-100"></div>\
					</div>\
				</div>\
			';
		}

		$('body').append(_temp);
	}

	$.unloading = function()
	{
		$('#__loader__, #__overlay_load__').fadeOut();
	}

	$.makePagination = function(tableId)
	{
		var $div = $(tableId).parents('.bootstrap-table');
		var options;

		_temp = '\
			<div class="bootstrap-table-pagination" hidden style="float: right;margin: 10px 0 10px 3px;">\
				<div style="line-height: 29px;float: left;margin-right: 3px">\
					Page <input style="width: 38px; text-align: center; font-size: 12px; height: 24px; margin: 0 5px" type="number" min="1" class="text-pagination-change"></input><span>&nbsp;of <b class="displayAllPage font-12"></b> pages&nbsp;</span>, \
					Showing <b class="displayFirst font-12"></b> - <b class="displayLast font-12"></b> of <b class="displayAll font-12"></b> rows\
				</div>\
				<div class="btn-group btn-pager">\
					<button class="btn btn-default btn-sm prev-page"  title="Previous Page"><i class="fa fa-chevron-left"></i></button>\
					<button class="btn btn-default btn-sm next-page"  title="Next Page"><i class="fa fa-chevron-right"></i></button>\
				</div>\
				<div class="btn-group pagination-size"></div>\
			</div>\
		';
		$div.prepend(_temp);

		$(tableId).on('post-body.bs.table', function(data)
		{
			options = $(tableId).bootstrapTable('getOptions');

			var pageSizeHtml = $div.find('.page-list').children('.btn-group').clone(true);
			var pageSize = $div.find('.pagination-size');
			var divDisplayFirst = $($div).find('.displayFirst');
			var divDisplayLast = $($div).find('.displayLast');
			var divDisplayAll = $($div).find('.displayAll');
			var divDisplayAllPage = $($div).find('.displayAllPage');
			// var divDisplayPage = $(div).find()

			$('.text-pagination-change').val(options.pageNumber);

			/* disabled pagination option */
			var lastPage = options.totalPages;

			if (options.pageNumber == 1)
			{
				$('.prev-page').prop('disabled', true);
			}
			else
			{
				$('.prev-page').prop('disabled', false);
			}

			if (options.pageNumber == lastPage)
			{
				$('.next-page').prop('disabled', true);
			}
			else
			{
				$('.next-page').prop('disabled', false);
			}

			if (options.data.length == options.totalRows)
			{
				$('.next-page').prop('disabled', true);
				$('.prev-page').prop('disabled', true);
			}

			// check if there's no data after searching, so the pagination print 0-0 of 0 instead of 1-0 of 0
			if (options.data.length == 0)
			{
				$(divDisplayFirst).html(0);
			}
			else
			{
				$(divDisplayFirst).html((options.pageNumber - 1) * options.pageSize + 1);
			}

			if (options.pageNumber * options.pageSize > options.totalRows)
			{
				$(divDisplayLast).html(options.totalRows);
			}
			else
			{
				$(divDisplayLast).html(options.pageNumber * options.pageSize);
			}

			$(divDisplayAll).html(options.totalRows);
			$(divDisplayAllPage).html(options.totalPages);

			// for data above 10 rows show the pagination size dropdown
			if (options.totalRows > 10)
			{
				$(pageSizeHtml).removeClass('dropup');
				$(pageSizeHtml).find('.dropdown-toggle').addClass('btn-sm');
				$(pageSizeHtml).find('.dropdown-menu').addClass('dropdown-menu-right');
				$(pageSize).html(pageSizeHtml);
			}
			else
			{
				$(pageSize).html('');
			}

			$div.find('.bootstrap-table-pagination').show();

			// fix page size element dropdown bootstrap table --- 28-06-2019 ---
			$('.pagination-size').off('click').on('click', function()
			{
				var chkPagiSizeExist = $('.pagination-size.clicked');

				if ( chkPagiSizeExist.length == 0 )
				{
					$(this).addClass('clicked');
				}
				else
				{
					chkPagiSizeExist.removeClass('clicked');
					$(this).addClass('clicked');
				}

				if ( $(this).hasClass('clicked') == true )
				{
					var getChildElementBtn = $(this).children().children('button');
					var getChildElementUl = $(this).children().children('ul');
					var getPageSize = getChildElementBtn.find('.page-size');
					var getUlActive = getChildElementUl.find('.active');

					if ( getUlActive.length > 0 )
					{
						getPageSize.text(getUlActive.text());
					}
				}
			});


		});

		$div.find('.prev-page').click(function()
		{
			$(tableId).bootstrapTable('prevPage');
		});
		$div.find('.next-page').click(function()
		{
			$(tableId).bootstrapTable('nextPage');
		});

		$div.find('.text-pagination-change').keypress(function(e)
		{
			var selectedPage = $('.text-pagination-change').val();

			if (e.which == 13)
			{
				// current page = $('.text-pagination-change').val();
				$(tableId).bootstrapTable('selectPage', selectedPage);
			}
		});
	}

	$.globalSearch = function(param)
	{
		var opts = $.extend(true, {
			disabled		: 	false,
			placeholder		: 	'Search',
			searchOnEnter	: 	true,
			useDropdown		: 	false,
			useButton		: 	true,
			dropdownHtml	: 	'',
			title			: 	'Advanced Search',
			globalTextId	: 	'__txtGlobalSearch__',
			globalTextLength : null,
			modalOptions	: 	{
									backdrop	: 'static',
									show		: false
			},
			buttonHtml		: 	null,
			searchFunction	: 	function() {return undefined;},
			afterRender 	:  	function() {return undefined;},
		}, param || {});

		var temp = '';

		if (opts.globalTextLength == null)
		{
			temp += '\
				<input style="display:none" type="text" name="dupechromeusername"/>\
				<input style="display:none" type="password" name="dupechromepassword"/>\
				<input type="text" class="form-control text-uppercase input-xsm" placeholder="'+ opts.placeholder +'" id="'+ opts.globalTextId +'">\
			';
		}
		else
		{
			temp += '\
				<input style="display:none" type="text" name="dupechromeusername"/>\
				<input style="display:none" type="password" name="dupechromepassword"/>\
				<input type="text" class="form-control text-uppercase input-xsm" placeholder="'+ opts.placeholder +'" id="'+ opts.globalTextId +'" maxlength="'+ opts.globalTextLength +'">\
			';
		}

		if (opts.useDropdown == true || opts.useButton == true)
		{
			$('#__divGlobalSearch__').addClass('input-group');

			temp += '<div class="input-group-btn">';

			if (opts.useDropdown == true)
			{
				if (!opts.buttonHtml)
				{
					opts.buttonHtml = '\
						<div>\
							<button type="button" class="btn btn-default" id="_btnLayoutModalReset"><i class="fa fa-undo"></i> Reset</button>\
							<button type="button" class="btn btn-primary" id="_btnLayoutModalSearch"><i class="fa fa-search"></i> Search</button>\
						</div>\
					';
				}

				$('body').append('\
					<div class="modal" id="_modalLayoutSearch">\
						<div class="modal-dialog">\
							<div class="modal-content">\
								<div class="modal-header">\
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>\
									<h4 class="modal-title bold-header" id="">'+ opts.title +'</h4>\
								</div>\
								<div class="modal-body">\
									\
								</div>\
								<div class="modal-footer">\
									'+ $(opts.buttonHtml).remove().html() +'\
								</div>\
							</div>\
						</div>\
					</div>\
				');

				$('#_modalLayoutSearch').modal(opts.modalOptions);

				temp += '\
					<button class="btn btn-default button-xsm" id="_btnGlobalSearch_">\
						<span class="caret"></span>\
					</button>\
				';

				var body = $(opts.dropdownHtml).html();
				$(opts.dropdownHtml).remove();

				$('#_modalLayoutSearch').find('.modal-body').html(body);
			}

			if (opts.useButton)
			{
				temp += '\
					<button class="btn btn-primary button-xsm" id="__btnSearch__">\
						<i class="fa fa-search"></i>\
					</button>\
				';
			}

			temp += '</div>';
		}
		
		$('#__divGlobalSearch__').html(temp);
		$('#'+opts.globalTextId).prop('disabled', opts.disabled);

		$('#_btnGlobalSearch_').click(function() {
			$('.has-error').find('.help-block').remove();
			$('.has-error').removeClass('has-error');
			
			$('#_modalLayoutSearch').modal('show');
		});

		if (opts.searchOnEnter == true)
		{
			$('#__txtGlobalSearch__').keypress(function (e) {
				var key = e.which;

				if (key == 13)  // the enter key code
				{
					$('#__btnSearch__').click();
					return false;  
				}
			});
		}

		if (opts.useButton == true)
		{
			$('#__btnSearch__').click(opts.searchFunction);
		}

		opts.afterRender();
	}

	$.inputError = function(param)
	{
		var opts = $.extend({
			inputId: '',
			message: ''
		}, param || {});

		var inputElement = $(opts.inputId);
		var temp = '';

		if( opts.message )
		{
			$(inputElement).parents('.form-group').addClass('has-error');

			if ($(inputElement).next('.help-block').length == 0)
			{
				temp += '\
					<small class="help-block">'+ opts.message +'</small>\
				';

				$(temp).insertAfter(inputElement);
			}
			else
			{
				$(inputElement).next('.help-block').text(opts.message);
			}
		}

		// $(inputElement).focus();
	}

	$.showError = function(error, prefix)
	{
		if(typeof(error) == 'object')
		{
			var output = '';

			if( !prefix ) {
				prefix = '#input';
			}

			for (var property in error)
			{
				if($(prefix + (property.charAt(0).toUpperCase() + property.slice(1))).length)
				{
					$.inputError({
						inputId: prefix + (property.charAt(0).toUpperCase() + property.slice(1)),
						message: error[property]
					});
				}
				else
				{
					output += (output == '') ? error[property] : '<br>' + error[property];
				}
			}
			if(output != '')
			{
				$.alert({
					text: output
				});
			}
		}
		else
		{
			$.alert({
				text: error
			});
		}
	}

	// Validasi di client side, on progress
	/*
		Parameter :
			- data : array yang berisi field-field, selector dan rule yang ingin di apply
				+ selector (String) : selector CSS dari input yang ingin diambil datanya 
				+ rules (Array) : rule yang ingin digunakan, dapat berupa string ataupun callback
					+ required
					+ string
					+ alphanumeric
					+ word (a-zA-Z0-9.-)
					+ email
					+ numeric
					+ minlength
					+ maxlength
					+ minvalue
					+ maxvalue
				+ messages
			- options :
				+ prefix: default '', sehingga semua selector ditambahkan prefix

		Contoh pemakaian :

		$.formValidator({
			data: [
				{ selector: 'platNomor', rules: ['required, 'string', 'alphanumeric', formatPlatMotor] },
				{ selector: 'umur', rules: ['required, 'numeric'] }
			],
			option: {
				prefix: '#input'
			}
		})
	*/
	$.displayClientError = function(message, prefix)
	{
		var alertMessages = [];

		if( !prefix ) {
			prefix = '#input';
		}

		for( var id in message )
		{
			if( $(prefix + id).length != 0 )
			{
				if( message[id] )
				{
					$.inputError({
						inputId: prefix + id,
						message: message[id]
					});
				}
				else
				{
					$.inputError({
						inputId: prefix + id,
						message: ''
					});
				}
			}
			else
			{
				alertMessages.push(message[id]);
			}
		}

		if( alertMessages.length > 0 ) {
			$.alert({
				text: alertMessages.join('<br/>')
			});
		}
	}

	$.formValidator = function( param )
	{
		var opts = $.extend({
			data: [],
			options: {}
		}, param || {});
	};

	$.serviceDayMaker = function(div,col,col2)
	{
		// var name = <?= Yii::t('app', 'SERVICE DAY') ?>;

		var temp = '<div class="col-sm-'+col+' searchServiceDay">\
						<label class="control-label">SERVICE DAY</label>\
						<p>\
							<span id="selectAll" onclick="checkAllDay(1)">ALL</span> | <span id="selectNone" onclick="checkAllDay(0)">NONE</span>\
						</p>\
					</div>';


		temp += '\
			<div class="col-sm-'+col2+'">\
				<div>\
					<table class="table table-compact table-borderless tableServDay">\
						<tr>\
							<td align="center"><div class="checkbox checkbox-primary"><input type="checkbox" class="chk-day checked styled servDayCheckbox" id="chkDay1" value="1" onchange="checkCeklistState()"><label></label></div></td>\
							<td align="center"><div class="checkbox checkbox-primary"><input type="checkbox" class="chk-day checked styled servDayCheckbox" id="chkDay2" value="2" onchange="checkCeklistState()"><label></label></div></td>\
							<td align="center"><div class="checkbox checkbox-primary"><input type="checkbox" class="chk-day checked styled servDayCheckbox" id="chkDay3" value="3" onchange="checkCeklistState()"><label></label></div></td>\
							<td align="center"><div class="checkbox checkbox-primary"><input type="checkbox" class="chk-day checked styled servDayCheckbox" id="chkDay4" value="4" onchange="checkCeklistState()"><label></label></div></td>\
							<td align="center"><div class="checkbox checkbox-primary"><input type="checkbox" class="chk-day checked styled servDayCheckbox" id="chkDay5" value="5" onchange="checkCeklistState()"><label></label></div></td>\
							<td align="center"><div class="checkbox checkbox-primary"><input type="checkbox" class="chk-day checked styled servDayCheckbox" id="chkDay6" value="6" onchange="checkCeklistState()"><label></label></div></td>\
							<td align="center"><div class="checkbox checkbox-primary"><input type="checkbox" class="chk-day checked styled servDayCheckbox" id="chkDay7" value="7" onchange="checkCeklistState()"><label></label></div></td>\
						</tr>\
						<tr style="font-family: Roboto Bold !important;">\
							<td align="center">1</td>\
							<td align="center">2</td>\
							<td align="center">3</td>\
							<td align="center">4</td>\
							<td align="center">5</td>\
							<td align="center">6</td>\
							<td align="center">7</td>\
						</tr>\
						<tr style="color: #bdbdbd !important;">\
							<td align="center">(MON)</td>\
							<td align="center">(TUE)</td>\
							<td align="center">(WED)</td>\
							<td align="center">(THU)</td>\
							<td align="center">(FRI)</td>\
							<td align="center">(SAT)</td>\
							<td align="center">(SUN)</td>\
						</tr>\
					</table>\
				</div>\
			</div>\
		';

		$('#'+div).replaceWith(temp);
	}

	$.serviceDayGetter = function()
	{
		var data = [];
		$('.servDayCheckbox').each(function(){
			if($(this).is(':checked'))
			{	
				data.push($(this).val());
			}
		});

		return data;
	}

	$.inputCheckboxDateMaker = function(div,col)
	{
		var temp = '<div class="col-sm-'+col+'">\
						<div class="col-sm-12 input-group groupCheckboxDateSSS">\
							<span class="input-group-addon groupCheckboxSSS">\
								<div class="checkbox checkbox-primary checkboxDateSSS"><input type="checkbox" class="chk-day checked styled" id="check_date" onchange="checkUseDate()"><label></label></div>\
							</span>\
							<div class="date input-date input-group" id="divDate">\
								<input type="text" class="form-control input-group" id="inputDateSSS" onkeypress="return dateKeypress(event)" maxlength="8" style="border-bottom-left-radius: 0px !important;border-bottom-right-radius: 0px !important;border-top-left-radius: 0px !important;border-top-right-radius: 0px !important;" disabled>\
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>\
							</div>\
						</div>\
					</div>';


		$('#'+div).replaceWith(temp);

		// setDatePicker();
	}

	//null empty string
	$.inputCheckboxDateGetter = function(cols)
	{
		if (typeof cols === "undefined" || cols === null) { 
			cols = '';
		}

		if($('#check_date').is(":checked"))
		{
			if($('#inputDateSSS').val())
			{
				return $('#inputDateSSS').val();
			}
			else
			{
				return cols;
			}
			
		}
		else
		{
			return cols;
		}
	}
	$.inputCheckboxDateSetter = function(data)
	{
		$('.input-date').data("DateTimePicker").date(moment(data,'YYYYMMDD'));
	}

	$.authAction = function(param)
	{
		_makeOverlay();
		var opts = $.extend({
			heading: 'Auth',
			text: '',
			buttonAlignClass: 'text-right',
			closeOnEscape: true,
			onClose: function() {},
			onCancel: function() {},
			onSubmit: function() {}
		}, param || {});

		_temp = '\
			<div class="__alert__ __panel__ panel panel-default">\
				<div class="panel-heading">'+ opts.heading +'</div>\
					<div class="p-4">'+ opts.text +'<br>\
						<div class="form-horizontal">\
							<div class="form-group">\
								<span for="" class="control-label col-sm-4">Username</span>\
								<div class="col-sm-8">\
									<input type="text" class="form-control" id="__inputUsername__">\
								</div>\
							</div>\
							<div class="form-group">\
								<span for="" class="control-label col-sm-4">Password</span>\
								<div class="col-sm-8">\
									<input type="password" class="form-control" id="__inputPassword__">\
								</div>\
							</div>\
						</div>\
					</div>\
					<div class="panel-footer text-right">\
						<button class="btn btn-default smaller-button __cancelButton__"><i class="fa fa-times"></i> Cancel</button>\
						<button class="btn btn-info smaller-button __submitButton__"><i class="fa fa-check"></i> Submit</button>\
					</div>\
				</div>\
			</div>\
		';

		$('body').append(_temp);

		var activePanel = $('.__alert__').last();
		var closeButton = $(activePanel).find('.__close__');
		var submitButton = $(activePanel).find('.__submitButton__');
		var cancelButton = $(activePanel).find('.__cancelButton__');

		$(closeButton).focus();

		$(closeButton).click(function()
		{
			_clear(activePanel);
			opts.onClose();
		});

		if (opts.closeOnEscape == true)
		{
			$(document).keypress(function(e) {
				if (e.keyCode == 27)
				{
					closeButton.click();
				}
			});
		}

		$(submitButton).click(function()
		{
			// _clear(activePanel);
			opts.onSubmit();
		});

		$('#__inputUsername__, #__inputPassword__').on('keypress', function(e)
		{
			if (e.keyCode == 13)
			{
				opts.onSubmit();
			}
		});

		$(cancelButton).click(function()
		{
			_clear(activePanel);
			opts.onCancel();
		});
	}

})(jQuery);

$(function() {

})

window.onerror = function (msg, url, lineNo, columnNo, error)
{
	var plane = 'images/plane';
	var bg_plane = 'images/bg-plane';	
	
	if(_theme.indexOf('dark')!=-1){
		plane += "-dark";
		bg_plane += "-dark";
	}

    var string = msg.toLowerCase();
    var substring = "script error";

    if (string.indexOf(substring) > -1)
	{
        alert('Script Error: See Browser Console for Detail');
    }
	else
	{
        var message = [
            'Message: ' + msg,
            // 'URL: ' + url,
            // 'Line: ' + lineNo,
            // 'Column: ' + columnNo,
            // 'Error object: ' + JSON.stringify(error)
        ].join(' - ');

        var messageToLog = {
        		message: msg,
        		url: url,
        		line: lineNo,
        		column: columnNo,
        		errorObject: error
        };

        var errorHTML = '\
				<div class="text-center">\
					<a href="' + _baseHostName + 'dashboard">\
						<img src="' + _baseHostName + 'data/demo/logo.png" height="35" alt=""/>\
					</a>\
				</div>\
				<!---->\
				<div class="content">\
					<div class="container-fluid">\
						<div class="col-sm-12">\
							<div class="m-1 text-center">\
								<div class="error-detail-div" id="unknown">\
									<div class="position-absolute l-50">\
										<div class="position-relative l-n50">\
											<img src="' + _baseHostName + bg_plane + '.png" height=280> </img>\
										</div>\
									</div>\
									<img class="error-icon my-5 py-2" src="' + _baseHostName + plane + '.png" height=150></img>\
									<h3 class="bold-header text-center mt-3 mb-0">There is script error.</h3><br>\
									<p class="text-center mt-n3">Please contact support team.</p>\
								</div>\
								<div class="text-center"><a class="btn btn-primary" href="'+ _baseHostName +'">Back to Home</a><br/><br/>\
								Details :</a></div><br/>\
								<pre>' + message + '</pre>\
							</div>\
						</div>\
					</div>\
				</div>\
			';

			$.ajax({
				method: 'POST',
				dataType: 'JSON',
				headers: {
		        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),
			   },
				url	: _baseHostName	+ 'error/client-side-error',
				data	: 	messageToLog
			});

	      $.alert({
				text: errorHTML,
				heading: 'Ooops!'
			});
    }

	$('#__loader__, #__overlay_load__').remove();
	$('div.panel-body').css('max-height','650px');
	$('div.panel-body').css('overflow','hidden');
	$('.panel-heading').hide();

	return false;
};

$(document).ajaxStart(function()
{
    $('#txtTemp2').focus();
	$.loading();
	$('.has-error').find('.help-block').remove();
	$('.has-error').removeClass('has-error');
});
$(document).ajaxStop(function()
{
	$.unloading();
});
$(document).ajaxComplete(function(event,xhr,options)
{
	try
	{
		$('#__serverTime__').html(xhr.responseJSON.processtime);
	} catch (e)
	{

	} finally
	{

	}
});
$(document).ajaxError(function(event,xhr,options,exc)
{
	if (xhr.status != 0)
	{
		$.alert({
			heading: xhr.status + ' - ' + xhr.statusText,
			text: xhr.responseText
		});
	}
});
$(document).on('hidden.bs.modal', '.modal', function ()
{
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

$(function()
{
	// automatic create cd top
	__makeCdTop__();

	// auto tooltip
	$('[data-toggle="popover"]').popover();
	$('[data-toggle="tooltip"], [data-hover="tooltip"]').tooltip();

	// all ajax will be post and json type by default
	$.ajaxSetup({
		type: 'POST',
		dataType: 'JSON',
	});
});

function __makeCdTop__()
{
	$('body').append('\
		<a class="cd-top text-info" title="go to top">\
			<i class="fa fa-2x fa-arrow-up"></i>\
		</a>\
	');

	var offset = 40,
		//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
		offset_opacity = 1200,
		//duration of the top scrolling animation (in ms)
		scroll_top_duration = 100,
		//grab the "back to top" link
		$back_to_top = $('.cd-top');

	//hide or show the "back to top" link
	$(window).scroll(function()
	{
		// console.log($(this).scrollTop());
		if ($(this).scrollTop() > offset)
		{
			$back_to_top.fadeIn(scroll_top_duration);
		}
		else
		{
			$back_to_top.fadeOut(scroll_top_duration);
		}

		if ($(this).scrollTop() > offset_opacity )
		{
			$back_to_top.addClass('cd-fade-out');
		}
	});

	//smooth scroll to top
	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		}, scroll_top_duration);
	});
}

function setDateRangeDefaultToday(date1, date2)
{
	// date1 < date2
	$(date1).datetimepicker({
		format : 'DD/MM/YYYY',
		useCurrent: false,
		minDate: moment(),
		ignoreReadonly: true,
		showTodayButton: true
	});

	$(date2).datetimepicker({
		format : 'DD/MM/YYYY',
		useCurrent: false,
		minDate: moment(),
		ignoreReadonly: true,
		showTodayButton: true,
	});

	$(date1).on("dp.change", function(e)
	{
		$(date2).data("DateTimePicker").minDate(e.date);
	});

	$(date2).on("dp.change", function(e)
	{
		$(date1).data("DateTimePicker").maxDate(e.date);
	});
}


// Showing loading screen when leaving a page
// window.onbeforeunload = function(e){
// 	// alert('test');

// 	if( !e ) e = window.event;

// 	e.stopPropagation();
// 	e.preventDefault();

// 	$.loading();
// };

// window.onload = function(e){
// 	$.unloading();
// }

function checkCeklistState()
{
	var checkAll = 1;
	var checkNone = 1;
	$('.servDayCheckbox').each(function(){
		if($(this).is(':checked'))
		{
			checkNone = 0;
		}
		else
		{
			checkAll = 0;
		}

	});

	if(checkAll == 1)
	{
		$('#selectAll').css('color','#71C6E5');
	}
	else
	{
		$('#selectAll').css('color','#a1a1a1');
	}

	if(checkNone == 1)
	{
		$('#selectNone').css('color','#71C6E5');
	}
	else
	{
		$('#selectNone').css('color','#a1a1a1');
	}
}

function checkAllDay(goons)
{

	if(goons)
	{
		$('.servDayCheckbox').each(function(){
			$(this).prop('checked',true);
			$('#selectAll').css('color','#71C6E5');
			$('#selectNone').css('color','#a1a1a1');
		});
	}
	else
	{
		$('.servDayCheckbox').each(function(){
			$(this).prop('checked',false);
			$('#selectNone').css('color','#71C6E5');
			$('#selectAll').css('color','#a1a1a1');
		});
	}
}

function checkUseDate()
{
	if($('#inputDateSSS').prop('disabled') == true)
	{
		$('#inputDateSSS').prop('disabled',false);
	}
	else
	{
		$('#inputDateSSS').prop('disabled',true);
	}
}
