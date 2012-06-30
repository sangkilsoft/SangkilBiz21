/**
 * edatagrid - jQuery EasyUI
 * 
 * Licensed under the GPL:
 *   http://www.gnu.org/licenses/gpl.txt
 *
 * Copyright 2011 stworthy [ stworthy@gmail.com ] 
 * 
 * Dependencies:
 *   datagrid
 *   messager
 * 
 */
(function($){
    function buildGrid(target){
        var opts = $.data(target, 'mdmegrid').options;
        $(target).datagrid($.extend({}, opts, {
            onClickCell:function(index,field){
                if(field == opts.buttonField){
                    setTimeout(function(){
                        $(target).datagrid('selectRow', opts.editIndex);
                    }, 0);
                    if (opts.onClickCell) opts.onClickCell.call(target, index,field);
                    return;                    
                }
                $(target).mdmegrid('editRow', {
                    index: index, 
                    field: field
                });        
                if (opts.onClickCell) opts.onClickCell.call(target, index,field);
            },
            onSelect: function(index,row){
                if(opts.columns){
                    for(var ic=0 ; ic < opts.columns.length; ic++){
                        var cls = opts.columns[ic];
                        for(var iic=0; iic < cls.length; iic++){
                            if(cls[iic].selector){
                                $(cls[iic].selector).val(row[cls[iic].field])                           
                            }
                        }
                    }
                }
                if(opts.frozenColumns){
                    for(var ic=0 ; ic < opts.frozenColumns.length; ic++){
                        var cls = opts.frozenColumns[ic];
                        for(var iic=0; iic < cls.length; iic++){
                            if(cls[iic].selector){
                                $(cls[iic].selector).val(row[cls[iic].field])                           
                            }
                        }
                    }
                }
                if (opts.onSelect) opts.onSelect.call(target, index,row);
            },
            onBeforeLoad: function(param){
                $(target).datagrid('rejectChanges');
                if (opts.onBeforeLoad) opts.onBeforeLoad.call(target, param);
            },
            onAfterEdit: function(index,row,changes){
                var c = 0;
                $.each(changes, function(){
                    c++;
                });
                if((c > 0) && (row.isNewRecord != true)){
                    row.updated = true;
                    $(target).datagrid('refreshRow',index);
                }                
                if (opts.onAfterEdit) opts.onAfterEdit.call(target, index,row,changes);
            },
            rowStyler: function(index,row){
                var r = '';
                if(row.deleted){
                    r = opts.deletedStyle;
                }else{
                    if(row.updated){
                        r = opts.updatedStyle;
                    }else{
                        if(row.isNewRecord){
                            r = opts.newRecordStyle;
                        }
                    }
                }  
                if (opts.rowStyler) 
                    r += opts.rowStyler.call(target, index,row);
                return r;
            }
        }));
        
    }
    
    $.extend($.fn.datagrid.defaults.editors, {  
        autocomplete: {  
            init: function(container, options){  
                var input = $('<input type="text" class="datagrid-editable-input">').appendTo(container);
                input.autocomplete(options).keydown(function(event){
                    event.stopPropagation();
                });
                return input;  
            },  
            getValue: function(target){  
                return $(target).val();  
            },  
            setValue: function(target, value){  
                $(target).val(value);  
            },  
            resize: function(target, width){  
                var input = $(target);  
                if ($.boxModel == true){  
                    input.width(width - (input.outerWidth() - input.width()));  
                } else {  
                    input.width(width);  
                }  
            }  
        },
        readonly: {  
            init: function(container, options){  
                var input = $('<input type="text" class="datagrid-editable-input">').appendTo(container);
                input.attr('readonly', 'readonly');
                return input;  
            },  
            getValue: function(target){  
                return $(target).val();  
            },  
            setValue: function(target, value){  
                $(target).val(value);  
            },  
            resize: function(target, width){  
                var input = $(target);  
                if ($.boxModel == true){  
                    input.width(width - (input.outerWidth() - input.width()));  
                } else {  
                    input.width(width);  
                }  
            }  
        }
    });      
	
    $.fn.mdmegrid = function(options, param){
        if (typeof options == 'string'){
            var method = $.fn.mdmegrid.methods[options];
            if (method){
                return method(this, param);
            } else {
                return this.datagrid(options, param);
            }
        }
		
        options = options || {};
        return this.each(function(){
            var state = $.data(this, 'mdmegrid');
            if (state){
                $.extend(state.options, options);
            } else {
                $.data(this, 'mdmegrid', {
                    options: $.extend({}, $.fn.mdmegrid.defaults, $.fn.mdmegrid.parseOptions(this), options)
                });
            }
            
            buildGrid(this);
        });
    };
	
    $.fn.mdmegrid.parseOptions = function(target){
        return $.extend({}, $.fn.datagrid.parseOptions(target), {
            });
    };
	
    $.fn.mdmegrid.methods = {
        options: function(jq){
            var opts = $.data(jq[0], 'mdmegrid').options;
            return opts;
        },
        enableEditing: function(jq){
            return jq.each(function(){
                var opts = $.data(this, 'mdmegrid').options;
                opts.allowEditing = true;
            });
        },
        disableEditing: function(jq){
            return jq.each(function(){
                var opts = $.data(this, 'mdmegrid').options;
                if($(this).mdmegrid('saveRow'))
                    opts.allowEditing = false;
            });
        },
        editRow: function(jq, param){
            return jq.each(function(){
                var dg = $(this);
                var opts = $.data(this, 'mdmegrid').options;
                if(!opts.allowEditing)
                    return;
                var index;
                if(typeof param == "object"){
                    index = param.index;
                }else{
                    index = param;
                }
                var editIndex = opts.editIndex;
                if(editIndex == index)
                    return;
                
                if(!dg.mdmegrid('saveRow'))
                    return;
                
                if(dg.mdmegrid('getRow', index).deleted)
                    return;
                dg.mdmegrid('beginEdit', param);
            });
        },
        addRow: function(jq){
            return jq.each(function(){
                var opts = $.data(this, 'mdmegrid').options;
                if(!opts.allowEditing)
                    return;
                var dg = $(this);
                if (!dg.mdmegrid('saveRow')){
                    return;
                }
                
                dg.datagrid('appendRow', {
                    firstEdit:true
                });
                var eIndex = dg.datagrid('getRows').length - 1;
                dg.mdmegrid('beginEdit', eIndex);
            });
        },
        beginEdit: function(jq,param){
            return jq.each(function(){                
                var opts = $.data(this, 'mdmegrid').options;
                if(opts.editIndex >= 0)
                    return;
                var dg = $(this);
                var index, field;
                if(typeof param == "object"){
                    index = param.index;
                    field = param.field;
                }else{
                    index = param;
                    field = undefined;
                }
                opts.editIndex = index;                
                var rows = dg.datagrid('getRows');
                rows[index].editing = true;
                dg.datagrid('refreshRow', index);
                if(opts.columns){
                    for(var ic=0 ; ic < opts.columns.length; ic++){
                        var cls = opts.columns[ic];
                        for(var iic=0; iic < cls.length; iic++){
                            if(typeof cls[iic].editor == 'object'){
                                if(cls[iic].editor.dynamicOpts){
                                    var dOpts = cls[iic].editor.dynamicOpts.call(rows[index],index);
                                    $.extend(cls[iic].editor.options,dOpts);
                                }                                
                            }
                        }
                    }
                }
                if(opts.frozenColumns){
                    for(var ic=0 ; ic < opts.frozenColumns.length; ic++){
                        var cls = opts.frozenColumns[ic];
                        for(var iic=0; iic < cls.length; iic++){
                            if(typeof cls[iic].editor == 'object'){
                                if(cls[iic].editor.dynamicOpts){
                                    var dOpts = cls[iic].editor.dynamicOpts.call(rows[index],index);
                                    $.extend(cls[iic].editor.options,dOpts);
                                }                                
                            }
                        }
                    }
                }
                dg.datagrid('beginEdit', index);
                dg.datagrid('selectRow', index);
                var rowCount = rows.length;
                var initEds = opts.initRowEditor;
                var evKeys = opts.evenKeydowns;
                var editors = dg.datagrid('getEditors', index);                
                if(editors.length){                      
                    var focused = false;                    
                    for(var i=0;i<editors.length;i++){
                        var cF = editors[i].field;
                        if(cF == field){
                            editors[i].target.focus();
                            focused = true;
                        }
                        if(i == (editors.length-1) && !focused){
                            editors[0].target.focus(); 
                            focused = true;
                        }
                        editors[i].target.data('mdmfield', cF);
                        editors[i].target.keydown(function(e){     
                            if(e.isPropagationStopped()) return;
                            var lF = $(this).data('mdmfield');
                            if(evKeys[lF]){
                                var ev = evKeys[lF];
                                if(ev(e)) return;
                            }
                            switch(e.which){
                                case 13:
                                    if(index < rowCount-1)
                                        dg.mdmegrid('editRow',parseInt(index)+1);
                                    else
                                        dg.mdmegrid('addRow');
                                    break;
                                case 40:
                                    if(index < rowCount-1)
                                        dg.mdmegrid('editRow',{
                                            index:parseInt(index)+1,
                                            field:lF
                                        });
                                    break;
                                case 38:
                                    if(index > 0)
                                        dg.mdmegrid('editRow',{
                                            index:parseInt(index)-1,
                                            field:lF
                                        });
                                    break;
                                case 27:
                                    dg.mdmegrid('cancelRow');
                                    break;
                                default:
                                    break;
                            }
                        });
                        if (initEds[cF]){
                            initEds[cF].call(editors[i].target,index,rows[index],editors);
                        }
                    }
                }
            });
        },
        saveRow: function(jq){
            var dg = $(jq[0]);            
            var opts = dg.mdmegrid('options');
            if(opts.editIndex >= 0){
                if (dg.datagrid('validateRow', opts.editIndex)){                
                    var rows = dg.datagrid('getRows');
                    rows[opts.editIndex].editing = undefined;
                    if(rows[opts.editIndex].firstEdit){                    
                        rows[opts.editIndex].firstEdit=undefined;
                        rows[opts.editIndex].isNewRecord = true;
                    }                
                    dg.datagrid('endEdit', opts.editIndex);
                    opts.editIndex = -1;
                    return true;
                }else{
                    setTimeout(function(){
                        dg.datagrid('selectRow', opts.editIndex);
                    }, 0);
                    return false;
                }
            }else{
                return true;
            }
        },
        cancelRow: function(jq){
            return jq.each(function(){
                var dg = $(this);
                var opts = dg.mdmegrid('options');
                if(opts.editIndex >= 0){                
                    var rows = dg.datagrid('getRows');
                    rows[opts.editIndex].editing = undefined;                    
                    dg.datagrid('cancelEdit', opts.editIndex);
                    if(rows[opts.editIndex].firstEdit){
                        dg.datagrid('deleteRow', opts.editIndex);
                    }
                    opts.editIndex = -1;
                }
            });
        },
        deleteRow: function(jq, index){
            return jq.each(function(){
                var dg = $(this);
                var rows = dg.datagrid('getRows');
                rows[index].deleted = true;
                var fe = rows[index].firstEdit;
                if(rows[index].editing){
                    dg.mdmegrid('cancelRow');
                }
                if(fe) return;
                if(rows[index].isNewRecord){
                    dg.datagrid('deleteRow',index);
                }
                dg.mdmegrid('refreshRow',index);
            });
        },
        cancelDelete: function(jq, index){
            return jq.each(function(){
                var rows = $(this).datagrid('getRows');
                rows[index].deleted = undefined;  
                $(this).mdmegrid('refreshRow',index);
            });
        },
        getRow: function(jq, index){
            var i;
            if(typeof index == 'undefined')
                i = $.data(jq[0], 'mdmegrid').options.editIndex;
            else
                i = index;
            var rows = $(jq[0]).datagrid('getRows');
            return rows[i];
        },
        getChanges: function(jq){
            var dg = $(jq[0]);
            dg.mdmegrid('saveRow');
            var rows = dg.datagrid('getRows');
            var ins = [] ,upd=[], d = [];
            for(var i=0; i < rows.length ; i++){
                if(rows[i].deleted){
                    d.push(rows[i]);
                }else{
                    if(rows[i].updated){
                        upd.push(rows[i]);
                    }else{
                        if(rows[i].isNewRecord){
                            ins.push(rows[i]);
                        }
                    }
                }                    
            }
            return {
                'updated':upd,
                'inserted':ins,
                'deleted':d
            };                
        },
        remoteCommit: function(jq){          
            return jq.each(function(){
                var dg = $(this);
                var opts = $.data(this, 'mdmegrid').options;
                if(!dg.mdmegrid('saveRow')){
                    return;
                }
                var param = dg.mdmegrid('getChanges');                
                if (opts.onBeforeCommit.call(this, param) == false) {
                    return;
                }
                $.ajax({
                    url:opts.saveUrl,
                    data:param,
                    type:'POST',
                    dataType: 'json',
                    success:function(data){
                        if(data.type == 'S'){
                            dg.mdmegrid('acceptChanges');
                            dg.datagrid('load');
                            opts.onCommit.call(this, data);
                        }else{
                            opts.onErrorCommit.call(this, data);
                        }
                    },
                    error:function(jqXHR, textStatus, errorThrown){
                        opts.onErrorCommit.call(this, {
                            type:'E',
                            message:errorThrown
                        })
                    }
                })
                
            });
        },
        acceptChanges:function(jq){
            return jq.each(function(){
                var dg=$(this);
                if(!dg.mdmegrid('saveRow')) return;
                var rows = dg.datagrid('getRows');
                for(var i=rows.length-1; i>=0 ; i--){
                    if(rows[i].deleted){
                        dg.datagrid('deleteRow',i);
                    }
                    if(rows[i].isNewRecord){
                        rows[i].isNewRecord = undefined;
                    }
                }
                dg.datagrid('acceptChanges');
            });
        }
    };
	
    $.fn.mdmegrid.defaults = $.extend({}, $.fn.datagrid.defaults, {
        allowEditing: true,
        editIndex: -1,		
        url: null,	// return the datagrid data
        saveUrl: null,	// return the added row
        buttonField: 'action',
        deletedStyle: 'background: #ccccdd; color: #222211;',
        updatedStyle: 'background: #ccddcc; color: #221122;',
        newRecordStyle: 'background: #ddcccc; color: #112222;',
		
        onBeforeCommit: function(rows){},
        onErrorCommit: function(data){},
        onCommit: function(data){},
        
        initRowEditor:{},
        evenKeydowns:{}
    });
})(jQuery);