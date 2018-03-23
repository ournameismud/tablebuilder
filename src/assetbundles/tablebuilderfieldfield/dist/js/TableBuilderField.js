/**
 * Table Builder plugin for Craft CMS
 *
 * TableBuilderField Field JS
 *
 * @author    @cole007
 * @copyright Copyright (c) 2018 @cole007
 * @link      http://ournameismud.co.uk/
 * @package   TableBuilder
 * @since     0.0.1TableBuilderTableBuilderField
 */

 ;(function ( $, window, document, undefined ) {

    var pluginName = "TableBuilderTableBuilderField",
        defaults = {};

    // Plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }
    

    Plugin.prototype = {
        
        clickSwitch: function(_container, _fieldname, _this) {
            // var _container = $(this).parents('.table-wrapper');
            var _table = $(_container).children('table');
            var _rows = $(_table).find('tbody tr.repeater');
            var _attr = $(_this).data('attr');
            var _parent = $(_this).parents('td,th');
            var _row = $(_this).parents('tr');
            switch (_attr) {
                case 'addCol':
                    console.log('addCol triggered');
                    var _cells = $(_row).find('th').length;
                    $(_table).find('tr.repeater').each(function() {
                        var _tr = this;
                        /*if ($(_tr).children('.heading').length) {
                            alert('kids');
                        } else */if ($(_tr).children('td').length) {
                            var cell = $(_tr).find('td:eq(0)').clone();
                            $(cell).find('input').val('');
                            $(_tr).find('td:last').before(cell);
                        } else if ($(_tr).children('th').length) {
                            var cell = $(_tr).find('th:eq(1)').clone();
                            $(cell).find('input').val('');
                            $(_tr).find('th:last').before(cell);
                        }
                    });
                    var delCol = $(_table).find('tfoot td').has('button').last().clone();
                    $(_table).find('tfoot td').has('button').last().after( delCol );

                    $(_container).find('.heading').parent('td').attr('colspan', (_cells - 1) );                            
                break;    
                case 'deleteRow':
                    console.log('deleteRow triggered');
                    if ($(_row).hasClass('repeater')) {
                        if(_rows.length > 1) $(_row).remove();
                        else {
                            alert('You must have at least one table row');
                        }
                    } else {
                        $(_row).remove();
                    }
                break;    
                case 'deleteCol':
                    
                    console.log('deleteCol triggered');
                    var pos = $(_row).find('td').index(_parent);
                    $(_table).find('tr.repeater').each(function() {
                        $(this).children('td,th').eq( pos ).remove();
                    });
                    var cols = $(_table).find('thead').find('input').length;
                    $(_table).find('tbody tr').not('.repeater').find('td:eq(0)').attr('colspan', cols );
                    $(_parent).css('border','10px solid red');
                    $(_parent).remove();
                    
                break;    
                case 'addRow':
                    console.log('addRow triggered');
                    
                    var _newRow = $(_table).find('tbody tr.repeater:first').clone();
                    $(_newRow).find('input').val('');
                    $(_row).after(_newRow);
                break;    
                case 'addHeading':
                    console.log('addHeading triggered');
                    var _cells = $(_container).find('thead th').length;
                    _cells -= 2;
                    var _newRow = '<tr>';
                    _newRow += '<th>';
                    _newRow += 'Subheading';
                    _newRow += '</th>';
                    _newRow += '<td colspan="' + _cells + '">';
                    _newRow += '<input class="heading" required placeholder="Table Subheading" type="text" /></td>';
                    _newRow += '<td nowrap>';
                    _newRow += '<button type="button" title="Insert Subheading Above" data-attr="addHeading">+</button> ';
                    _newRow += '<button type="button" title="Insert Row Below" data-attr="addRow">+</button> ';
                    _newRow += '<button type="button" title="Delete This Row" data-attr="deleteRow">-</button> ';
                    _newRow += '<button type="button" class="draggable" title="Move This Row" data-attr="dragRow">|</button> ';
                    _newRow += '</td></tr>';
                    $(_row).before(_newRow);
                break;    
            }
            $(_table).tableDnDUpdate();

            _rows = $(_table).find('tbody tr').filter('.repeater');
            if (_rows.length == 1) {
                $(_table).find('.draggable').addClass('disabled');
            } else {
                $(_table).find('.draggable').removeClass('disabled');
            }
            
            this.serializeInputs(_container, _fieldname);
        },
        serializeInputs: function(_container, _fieldname) {
                        
            $(_container).find('tr').each(function(i) {
                var _base = i;
                if ($(this).hasClass('heading')) {
                    $(this).find('input').attr('name','fields[' + _fieldname + '][heading][]');
                } else if ($(this).hasClass('repeater')) {
                    $(this).find('input').attr('name','fields[' + _fieldname + '][row]['+_base+'][]');
                } else {
                    $(this).find('input').attr('name','fields[' + _fieldname + '][row]['+_base+'][subheading]');
                }
            });
        },

        init: function(id) {
            var _this = this;
            
            $(function () {
                
                $('.table-wrapper').each(function() {
                    var _container = $(this);
                    var _fieldname = $(this).data('field');
                    var _table = $(_container).children('table');
                    _this.serializeInputs(_container, _fieldname);
                    
                    var _rows = $(_table).find('tbody tr.repeater');

                    $(_table).tableDnD({
                        dragHandle: '.draggable',
                        onDragClass: 'dragging',
                        onDrop: function() {
                            _this.serializeInputs(_container, _fieldname);
                        }
                    });
                    
                    if (_rows.length == 1) {
                        $(_table).find('.draggable').addClass('disabled');
                    }
                    
                    $(_container).off().on('click','button',function() {
                        _this.clickSwitch(_container, _fieldname, this);                        
                    });
                });
                                
            });
            // some kinda blur thingy or page save event ??
        }
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );
