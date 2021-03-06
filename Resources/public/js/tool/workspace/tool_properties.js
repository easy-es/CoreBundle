/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

(function () {
    'use strict';

    var stackedRequests = 0;
    $.ajaxSetup({
        beforeSend: function () {
            stackedRequests++;
            $('.please-wait').show();
        },
        complete: function () {
            stackedRequests--;
            if (stackedRequests === 0) {
                $('.please-wait').hide();
            }
        }
    });

    var wsId = $('#tool-table').attr('data-workspace-id');

    $('.chk-tool-visible').on('change', function (e) {
        var toolId = $(e.target.parentElement).attr('data-tool-id');
        var roleId = $(e.target.parentElement).attr('data-role-id');
        var isCurrentElementChecked = e.currentTarget.checked;
        var route = '';
        if (isCurrentElementChecked) {
            route = Routing.generate(
                'claro_tool_workspace_add',
                { 'toolId' : toolId, 'workspaceId': wsId, 'roleId': roleId }
            );
        } else {
            route = Routing.generate(
                'claro_tool_workspace_remove',
                { 'toolId' : toolId, 'workspaceId': wsId, 'roleId': roleId }
            );
        }
        $.ajax({url: route, type: 'POST'});
    });

    $('.fa-arrow-circle-up').on('click', function (e) {
        var rowIndex = e.target.parentElement.parentElement.rowIndex;
        moveRowUp(rowIndex);
    });

    $('.fa-arrow-circle-down').on('click', function (e) {
        var rowIndex = e.target.parentElement.parentElement.rowIndex;
        moveRowDown(rowIndex);
    });

	function moveRowUp(index) {
        var toolId;
        var isRowChecked = false;
        var rows = $('#tool-table tr');
        $(rows.eq(index)[0].children).each(function (i, e) {
            if ($(e.children[0]).attr('checked') === 'checked') {
                isRowChecked = true;
            }
        });
        if (index !== 1) {
            rows.eq(index).insertBefore(rows.eq(index - 1));
            if (isRowChecked) {
                toolId = $(rows.eq(index)[0].children[1]).attr('data-tool-id');
                index = index - 1;
            } else {
                toolId = $(rows.eq(index - 1)[0].children[1]).attr('data-tool-id');
            }
            var route = Routing.generate(
                'claro_tool_workspace_move',
                { 'toolId': toolId, 'position': index, 'workspaceId': wsId }
            );
            $.ajax({url: route, type: 'POST'});
            setOrderingIconsState();
        }
	}

    function moveRowDown(index) {
        var rows = $('#tool-table tr');
        rows.eq(index).insertAfter(rows.eq(index + 1));
        var size = $('#tool-table tr').length;
        var isRowChecked = false;
        var toolId;

        $(rows.eq(index)[0].children).each(function (i, e) {
            if ($(e.children[0]).attr('checked') === 'checked') {
                isRowChecked = true;
            }
        });
        rows.eq(index).insertAfter(rows.eq(index + 1));
        if (index !== size) {
            if (isRowChecked) {
                toolId = $(rows.eq(index)[0].children[1]).attr('data-tool-id');
                index = index + 1;
            } else {
                toolId = $(rows.eq(index + 1)[0].children[1]).attr('data-tool-id');
            }
            var route = Routing.generate(
                'claro_tool_workspace_move',
                { 'toolId': toolId, 'position': index, 'workspaceId': wsId }
            );
            $.ajax({url: route, type: 'POST'});
            setOrderingIconsState();
        }
    }

    function setOrderingIconsState() {
        var upIcons = $('#tool-table span.ordering-icon.up');
        var downIcons = $('#tool-table span.ordering-icon.down');
        var downLength = downIcons.length;

        upIcons.each(function (index, icon) {
            $(icon)[(index === 0 ? 'addClass' : 'removeClass')]('disabled');
        });
        downIcons.each(function (index, icon) {
            $(icon)[index === downLength - 1 ? 'addClass' : 'removeClass']('disabled');
        });
    }
})();

