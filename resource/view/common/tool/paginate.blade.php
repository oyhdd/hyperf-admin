<?php

    $current_page = $dataProvider->currentPage();
    $first_page = 1;
    $last_page = $dataProvider->lastPage();
?>

<div class="dataTables_paginate paging_simple_numbers" id="{{$tableId}}_paginate">
    <ul class="pagination pagination mt-2 mb-0" style="white-space: nowrap; justify-content: flex-end;">
        <li class="paginate_button page-item previous {{ empty($dataProvider->previousPageUrl()) ? 'disabled' : '' }}" id="{{$tableId}}_previous">
            @php
                $pathQuery['page'] = $current_page - 1;
                $href = $_path.'?'.http_build_query($pathQuery);
            @endphp
            <a href="{{ $href }}" tabindex="0" class="page-link">上一页</a>
        </li>
        @php

            $html = '';
            if ($last_page <= $first_page + 6) {
                for ($i = $first_page; $i <= $last_page; $i++) {
                    $active = $current_page == $i ? "active" : "";
                    $pathQuery['page'] = $i;
                    $href = $_path.'?'.http_build_query($pathQuery);
                    $html .= "<li class='paginate_button page-item {$active}' ><a href='{$href}' tabindex='0' class='page-link'>{$pathQuery['page']}</a></li>";
                }
            } else {
                if ($current_page - 3 <= $first_page) {
                    for ($i = $first_page; $i <= $first_page + 4; $i++) {
                        $active = $current_page == $i ? "active" : "";
                        $pathQuery['page'] = $i;
                        $href = $_path.'?'.http_build_query($pathQuery);
                        $html .= "<li class='paginate_button page-item {$active}' ><a href='{$href}' tabindex='0' class='page-link'>{$pathQuery['page']}</a></li>";
                    }
                } else {
                    $pathQuery['page'] = $first_page;
                    $href = $_path.'?'.http_build_query($pathQuery);
                    $html .= "<li class='paginate_button page-item'><a href='{$href}' tabindex='0' class='page-link'>{$pathQuery['page']}</a></li>";
                    $html .= "<li class='paginate_button page-item disabled'><a href='#' tabindex='0' class='page-link'>…</a></li>";

                    if ($current_page + 3 < $last_page) {
                        for ($i = $current_page - 1; $i <= $current_page + 1; $i++) {
                            $active = $current_page == $i ? "active" : "";
                            $pathQuery['page'] = $i;
                            $href = $_path.'?'.http_build_query($pathQuery);
                            $html .= "<li class='paginate_button page-item {$active}' ><a href='{$href}' tabindex='0' class='page-link'>{$pathQuery['page']}</a></li>";
                        }
                    }
                }
                if ($current_page + 3 >= $last_page) {
                    for ($i = $last_page - 4; $i <= $last_page; $i++) {
                        $active = $current_page == $i ? "active" : "";
                        $pathQuery['page'] = $i;
                        $href = $_path.'?'.http_build_query($pathQuery);
                        $html .= "<li class='paginate_button page-item {$active}'><a href='{$href}' tabindex='0' class='page-link'>{$pathQuery['page']}</a></li>";
                    }
                } else {
                    $html .= "<li class='paginate_button page-item disabled'><a href='#' tabindex='0' class='page-link'>…</a></li>";
                    $pathQuery['page'] = $last_page;
                    $href = $_path.'?'.http_build_query($pathQuery);
                    $html .= "<li class='paginate_button page-item'><a href='{$href}' tabindex='0' class='page-link'>{$pathQuery['page']}</a></li>";
                }
            }

            echo $html;
        @endphp
        <li class="paginate_button page-item next {{ empty($dataProvider->nextPageUrl()) ? 'disabled' : '' }}" id="{{$tableId}}_next">
            @php
                $pathQuery['page'] = $current_page + 1;
                $href = $_path.'?'.http_build_query($pathQuery);
            @endphp
            <a href="{{$href}}" tabindex="0" class="page-link">下一页</a>
        </li>
    </ul>
</div>