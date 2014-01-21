<?php
/**
 * Drop downs
 */
function abcfic_cbos_quality() {
    return array('50' => '50 %',
        '60' => '60 %',
        '70' => '70 %',
        '80' => '80 %',
        '90' => '90 %',
        '100' => '100 %');
}

function abcfic_cbos_yn() {
    return array('0' => abcfic_inputbldr_txt(93),
                '1' => abcfic_inputbldr_txt(92));
}


function abcfic_cbos_target() {
    return array('0' => abcfic_inputbldr_txt(36),
                '1' => abcfic_inputbldr_txt(37));
}
