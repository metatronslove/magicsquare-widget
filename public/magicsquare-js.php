<?php
/**
 * Magic Square Widget Dynamic JavaScript Generator
 *
 * This file generates the complete Magic Square widget functionality.
 * All UI, event listeners, and magic square algorithms are included here.
 *
 * @package MagicSquareWidget
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    header( 'Content-Type: text/plain' );
    echo '// Direct access not allowed';
    exit;
}

header( 'Content-Type: application/javascript; charset=UTF-8' );
header( 'X-Content-Type-Options: nosniff' );

if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
    header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
} else {
    header( 'Cache-Control: public, max-age=3600' );
    header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 3600 ) . ' GMT' );
}

// =========================================================================
// VERİTABANINDAN TÜM AYARLARI OKU
// =========================================================================

$magic_square_options = get_option( 'magic_square_widget_settings', array(
    'id'            => 'metatronslove', // SABİT - kullanıcı değiştiremez
    'color'         => '#FFDD00',
    'position'      => 'right',
    'margin_x'      => 18,
    'margin_y'      => 18,
    'message'       => 'Like my projects? Buy me a coffee!',
    'description'   => 'Support my work on magic squares',
    'enabled'       => 1,
    'button_type'   => 'emoji',
    'button_emoji'  => '🪄',
    'button_svg'    => '',
    'button_png_url' => ''
) );

$magic_square_style_options = get_option( 'magic_square_widget_style', array( 'custom_css' => '' ) );
$magic_square_code_options  = get_option( 'magic_square_widget_code', array( 'custom_js' => '' ) );

$magic_square_plugin_url = plugin_dir_url( __FILE__ );

// =========================================================================
// BUTON İÇERİĞİNİ OLUŞTUR
// =========================================================================

$magic_square_button_content = '🪄';
switch ( $magic_square_options['button_type'] ) {
    case 'emoji':
        $magic_square_button_content = ! empty( $magic_square_options['button_emoji'] ) ? $magic_square_options['button_emoji'] : '🪄';
        break;
    case 'svg':
        $magic_square_button_content = ! empty( $magic_square_options['button_svg'] ) ? trim( $magic_square_options['button_svg'] ) : '🪄';
        break;
    case 'png':
        $magic_square_button_content = ! empty( $magic_square_options['button_png_url'] )
            ? '<img src="' . esc_url( $magic_square_options['button_png_url'] ) . '" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">'
            : '🪄';
        break;
}

// =========================================================================
// TÜM JAVASCRIPT KODU - OFFLINE SÜRÜMDEN BİREBİR (ID'ler widget'a uyarlandı)
// =========================================================================
$magic_square_complete_js = <<<'EOT'
// =========================================================================
// YARDIMCI FONKSİYONLAR
// =========================================================================

class MagicSquareWidgetHelper {
    constructor(widgetId) {
        this.widgetId = widgetId;            // Her widget için benzersiz ID
        this.i18n = window.magicSquareI18n;        
    }
    
    __(key) {
        return this.i18n && this.i18n[key] ? this.i18n[key] : key;
    }

    getElement(id) {
        return document.getElementById(this.widgetId + '-' + id);
    }
}
(function(global, $) {
    'use strict';

    // =========================================================================
    // GLOBAL STATE
    // =========================================================================
    window.magicSquareWidgetId = 'ms-' + Math.random().toString(36).substr(2, 8);
    window.widgetState = { activeTab: 'text' };
    window.magicSquareI18n = typeof global !== 'undefined' && global.magicSquareData ? global.magicSquareData.i18n || {} : {};
    const mshelper = new MagicSquareWidgetHelper(window.magicSquareWidgetId);
    
    // =========================================================================
    // ORİJİNAL ANİMASYON FONKSİYONLARI (offline'dan)
    // =========================================================================
    window.HideAndSeek = function(tohide, toshow, duration, delay) {
        setTimeout(function() {
            tohide.forEach(function(sel) {
                document.querySelectorAll(sel).forEach(function(el) {
                    el.style.transition = 'opacity ' + duration + 'ms';
                    el.style.opacity = '0';
                    setTimeout(function() { el.style.display = 'none'; }, duration);
                });
            });
            toshow.forEach(function(sel) {
                document.querySelectorAll(sel).forEach(function(el) {
                    el.style.display = '';
                    el.style.transition = 'opacity ' + duration + 'ms';
                    el.style.opacity = '1';
                });
            });
        }, delay);
    };

    window.HideAndView = function(order) {
        var firstEl = document.getElementById(order[0].toString());
        if (!firstEl) return;
        var currentlyHidden = firstEl.style.display === 'none';
        order.forEach(function(id) {
            var el = document.getElementById(id.toString());
            if (el) {
                el.style.display = currentlyHidden ? '' : 'none';
            }
        });
    };
 
    // =========================================================================
    // SEKMELER (Widget'a özgü)
    // =========================================================================
    function switchTab(tabId) {
        const wid = window.magicSquareWidgetId;
        window.widgetState.activeTab = tabId;
        
        // Sekme butonlarını güncelle
        document.querySelectorAll('.' + wid + '-tab').forEach(function(t) {
            t.classList.toggle('active', t.dataset.tab === tabId);
        });
        
        // Tüm kontrolleri ve output alanlarını al
        const controls = document.querySelector('.' + wid + '-controls');
        const outputArea = document.querySelector('.' + wid + '-output-area');
        const tabContentElement = document.querySelector('.' + wid + '-content .' + wid + '-tab-content[data-tab="support"]');
        
        // Tüm output container'larını al
        const magicsquareoutput = document.getElementById(wid + '-magicsquareoutput');
        const textOutput = document.getElementById(wid + '-textoutput');
        const tabledOutput = document.getElementById(wid + '-tabledoutput');
        const htmlOutput = document.getElementById(wid + '-htmloutputcontainer');
        const pdfpngOutput = document.getElementById(wid + '-pdfpngoutput');
        
        // Tüm option alanlarını al
        const textOptions = document.getElementById(wid + '-textoptions');
        const tabledOptions = document.getElementById(wid + '-tabledoptions');
        const htmlOptions = document.getElementById(wid + '-htmloptions');
        const pdfOptions = document.getElementById(wid + '-pdfoptions');
        
        // Önce her şeyi gizle
        if (textOutput) textOutput.style.display = 'none';
        if (tabledOutput) tabledOutput.style.display = 'none';
        if (htmlOutput) htmlOutput.style.display = 'none';
        if (pdfpngOutput) pdfpngOutput.style.display = 'none';
        
        if (textOptions) textOptions.style.display = 'none';
        if (tabledOptions) tabledOptions.style.display = 'none';
        if (htmlOptions) htmlOptions.style.display = 'none';
        if (pdfOptions) pdfOptions.style.display = 'none';
        
        // SUPPORT SEKMESİ - tüm kontrolleri gizle, sadece iframe görünsün
        if (tabId === 'support') {
            if (controls) controls.style.display = 'none';
            if (outputArea) outputArea.style.display = 'none';
            if (tabContentElement) tabContentElement.style.display = 'block';
            return;
        }
        
        // Diğer sekmelerde kontrolleri göster
        if (controls) controls.style.display = 'flex';
        if (outputArea) outputArea.style.display = 'block';
        
        // Copy/Save butonlarını al
        const copyBtn = document.getElementById(wid + '-copybtn');
        const saveBtn = document.getElementById(wid + '-savebtn');
        
        // Aktif sekmeye göre göster
        if (tabId === 'text') {
            if (textOutput) textOutput.style.display = 'block';
            if (magicsquareoutput) magicsquareoutput.setAttribute('omode', '0');
            if (textOptions) textOptions.style.display = 'block';
            if (copyBtn) copyBtn.disabled = false;
            if (saveBtn) saveBtn.disabled = false;
            
        } else if (tabId === 'tabled') {
            if (tabledOutput) tabledOutput.style.display = 'block';
            if (magicsquareoutput) magicsquareoutput.setAttribute('omode', '1');
            if (tabledOptions) tabledOptions.style.display = 'block';
            if (copyBtn) copyBtn.disabled = false;
            if (saveBtn) saveBtn.disabled = false;
            
        } else if (tabId === 'html') {
            if (htmlOutput) htmlOutput.style.display = 'block';
            if (magicsquareoutput) magicsquareoutput.setAttribute('omode', '2');
            if (htmlOptions) htmlOptions.style.display = 'block';            
            if (pdfOptions) pdfOptions.style.display = 'block';
            if (copyBtn) copyBtn.disabled = false;
            if (saveBtn) saveBtn.disabled = false;
            
            // HTML highlight'ı güncelle
            setTimeout(function() {
                if (typeof window.highlightCode === 'function') {
                    const htmlOut = document.getElementById(wid + '-htmloutput');
                    const highlighted = document.getElementById(wid + '-highlightedoutput');
                    if (htmlOut && highlighted && htmlOut.value) {
                        window.highlightCode(htmlOut, highlighted);
                    }
                }
            }, 100);
            
        } else if (tabId === 'pdf') {
            if (pdfpngOutput) pdfpngOutput.style.display = 'block';            
            if (htmlOptions) htmlOptions.style.display = 'block';
            if (pdfOptions) pdfOptions.style.display = 'block';
            if (copyBtn) copyBtn.disabled = true;
            if (saveBtn) saveBtn.disabled = false;
            
        } else if (tabId === 'png') {
            if (pdfpngOutput) pdfpngOutput.style.display = 'block';            
            if (htmlOptions) htmlOptions.style.display = 'block';            
            if (pdfOptions) pdfOptions.style.display = 'block';
            if (copyBtn) copyBtn.disabled = true;
            if (saveBtn) saveBtn.disabled = false;
        }
    }

    // =========================================================================
    // KOPYALA FONKSİYONU (offline'dan)
    // =========================================================================
    window.copyToClipboard = function() {
        const wid = window.magicSquareWidgetId;
        const omode = parseInt(document.getElementById(wid + '-magicsquareoutput').getAttribute('omode'));
        let copySource = "";
        let successMessage = "";
        
        if (omode == 0) {
            copySource = wid + "-magicsquareoutput";
            successMessage = mshelper.__('copySuccess') + ' ' + mshelper.__('magicSquare');
        } else if (omode == 1) {
            copySource = wid + "-boxedoutput";
            successMessage = mshelper.__('copySuccess') + ' ' + mshelper.__('tabTabled');
        } else if (omode == 2) {
            copySource = wid + "-htmloutput";
            successMessage = mshelper.__('copySuccess') + ' ' + mshelper.__('tabHtml');
        } else {
            return;
        }
        
        const textarea = document.getElementById(copySource);
        if (!textarea) return;
        
        textarea.select();
        document.execCommand('copy');
        alert(successMessage);
    };

    // =========================================================================
    // KAYDET FONKSİYONU (offline'dan)
    // =========================================================================
    window.saveToLocalDisk = function() {
        const wid = window.magicSquareWidgetId;
        const activeTab = window.widgetState.activeTab;
        const filename = generateFilename();
        
        if (activeTab === 'text') {
            const output = document.getElementById(wid + '-magicsquareoutput');
            if (output) {
                downloadText(output.value, filename + '.txt');
            }
        } else if (activeTab === 'tabled') {
            const output = document.getElementById(wid + '-boxedoutput');
            if (output) {
                downloadText(output.value, filename + '.txt');
            }
        } else if (activeTab === 'html') {
            const output = document.getElementById(wid + '-htmloutput');
            if (output) {
                downloadText(output.value, filename + '.html');
            }
        } else if (activeTab === 'pdf') {
            saveAsPdf(filename);
        } else if (activeTab === 'png') {
            saveAsPng(filename);
        }
    };

    function downloadText(text, filename) {
        text = text.replace(/\n/g, "\r\n");
        const blob = new Blob([text], { type: "text/plain" });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
        URL.revokeObjectURL(link.href);
    }

    function generateFilename() {
        const wid = window.magicSquareWidgetId;
        const date = new Date();
        const day = ("0" + date.getDate()).slice(-2);
        const month = ("0" + (date.getMonth() + 1)).slice(-2);
        const year = date.getFullYear();
        const n = mshelper.getElement('size')?.value;
        const sum = document.getElementById(wid + '-rowsum')?.value || 15;
        
        return `[${n}x${n}-Magic-Square-${sum}] [${day}-${month}-${year}]`;
    }

    function saveAsPdf(filename) {
        const wid = window.magicSquareWidgetId;
        const container = document.getElementById(wid + '-pdfpngoutput');
        if (!container) return;
        
        const table = container.querySelector('.magic-square-table');
        if (!table) return;
        
        const paperSize = document.getElementById(wid + '-papersize')?.value || 'A4P';
        let orientation = 'portrait';
        let format = 'a4';
        
        if (paperSize === 'A5P') { format = 'a5'; orientation = 'portrait'; }
        else if (paperSize === 'A5L') { format = 'a5'; orientation = 'landscape'; }
        else if (paperSize === 'A4P') { format = 'a4'; orientation = 'portrait'; }
        else if (paperSize === 'A4L') { format = 'a4'; orientation = 'landscape'; }
        else if (paperSize === 'A3P') { format = 'a3'; orientation = 'portrait'; }
        else if (paperSize === 'A3L') { format = 'a3'; orientation = 'landscape'; }
        
        html2canvas(container, { scale: 2 }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jspdf.jsPDF({
                orientation: orientation,
                unit: 'mm',
                format: format
            });
            const imgWidth = pdf.internal.pageSize.getWidth();
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
            pdf.save(filename + '.pdf');
        });
    }

    function saveAsPng(filename) {
        const wid = window.magicSquareWidgetId;
        const container = document.getElementById(wid + '-pdfpngoutput');
        if (!container) return;
        
        const table = container.querySelector('.magic-square-table');
        if (!table) return;
        
        html2canvas(container, { scale: 2 }).then(canvas => {
            const link = document.createElement('a');
            link.download = filename + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }

    // =========================================================================
    // ORİJİNAL CSS (Widget'a özgü)
    // =========================================================================
    if (typeof window.magicSquareCustomCSS === 'undefined') {
        var style = document.createElement('style');
        style.textContent = `
.${window.magicSquareWidgetId}-container * { box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 0; padding: 0; }
.${window.magicSquareWidgetId}-container {
    position: fixed;
    bottom: ${window.config.margin_y}px;
    ${window.config.position}: ${window.config.margin_x}px;
    z-index: 4900;
    font-size: 14px;
}
.${window.magicSquareWidgetId}-button {
    width: 56px; height: 56px; border-radius: 50%; background: ${window.config.color};
    cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: flex; align-items: center; justify-content: center; font-size: 24px;
    transition: all 0.2s; color: #ffffff; border: none;
}
.${window.magicSquareWidgetId}-button:hover { transform: scale(1.05); box-shadow: 0 8px 20px rgba(0,0,0,0.2); }
.${window.magicSquareWidgetId}-button:active { transform: scale(0.95); }
.${window.magicSquareWidgetId}-modal {
    position: absolute; bottom: 70px; ${window.config.position}: 0;
    width: 520px; height: 600px; background: #ffffff; border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15); display: none; flex-direction: column;
    color: #1e293b; overflow: hidden;
}
.${window.magicSquareWidgetId}-tabs {
    display: flex; background: #f8fafc; border-bottom: 1px solid #e2e8f0;
    padding: 0 16px; gap: 8px; height: 50px; flex-shrink: 0;
}
.${window.magicSquareWidgetId}-tab {
    padding: 14px 12px; cursor: pointer; font-weight: 500; color: #64748b;
    font-size: 13px; border-bottom: 2px solid transparent; transition: all 0.2s;
}
.${window.magicSquareWidgetId}-tab:hover { color: #334155; }
.${window.magicSquareWidgetId}-tab.active { color: ${window.config.color}; border-bottom-color: ${window.config.color}; }
.${window.magicSquareWidgetId}-content {
    padding: 15px;
    overflow-y: auto;
    height: 530px;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.${window.magicSquareWidgetId}-controls {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    background: #f8fafc;
    border-radius: 12px;
    padding: 12px;
    border: 1px solid #e2e8f0;
}
.${window.magicSquareWidgetId}-formshaper-left, .${window.magicSquareWidgetId}-formshaper-right {
    flex: 1;
    min-width: 220px;
}
.${window.magicSquareWidgetId}-form-group {
    margin-bottom: 10px;
    width: 100%;
}
.${window.magicSquareWidgetId}-form-group label {
    display: block;
    margin-bottom: 4px;
    color: #475569;
    font-weight: 500;
    font-size: 12px;
}
.${window.magicSquareWidgetId}-form-group input,
.${window.magicSquareWidgetId}-form-group select {
    width: 100%;
    padding: 6px 8px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    color: #1e293b;
    font-size: 12px;
    outline: none;
}
.${window.magicSquareWidgetId}-form-group input:focus,
.${window.magicSquareWidgetId}-form-group select:focus {
    border-color: ${window.config.color};
    box-shadow: 0 0 0 3px rgba(255, 221, 0, 0.1);
}
.${window.magicSquareWidgetId}-button-group {
    display: flex;
    gap: 6px;
    margin: 6px 0;
    flex-wrap: wrap;
}
.${window.magicSquareWidgetId}-button-group button {
    padding: 6px 10px;
    background: ${window.config.color};
    color: #1e293b;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.2s;
    flex: 1;
    min-width: 70px;
}
.${window.magicSquareWidgetId}-button-group button:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
.${window.magicSquareWidgetId}-button-group button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    transform: none;
}
.checkbox-slider {
    margin: 0;
    flex-shrink: 0;
}
.checkbox-slider label {
    display: block;
    width: 50px;
    height: 26px;
    background-color: #ccc;
    border-radius: 13px;
    position: relative;
    cursor: pointer;
}
.checkbox-slider label::before {
    content: '';
    display: block;
    width: 22px;
    height: 22px;
    background-color: #fff;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 2px;
    transition: transform 0.2s;
}
.checkbox-slider input[type="checkbox"] { display: none; }
.checkbox-slider input[type="checkbox"]:checked + label { background-color: #28a745; }
.checkbox-slider input[type="checkbox"]:checked + label::before { transform: translateX(24px); }
.${window.magicSquareWidgetId}-output-area {
    background: #f8fafc;
    border-radius: 12px;
    padding: 12px;
    border: 1px solid #e2e8f0;
}
.${window.magicSquareWidgetId}-output-area textarea {
    width: 100%;
    min-height: 120px;
    padding: 10px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    color: #1e293b;
    font-size: 12px;
    line-height: 1.5;
    resize: vertical;
    font-family: 'SF Mono', Monaco, Consolas, monospace;
    margin: 5px 0;
}
#${window.magicSquareWidgetId}-textoutput, #${window.magicSquareWidgetId}-tabledoutput {
    display: block;
}
#${window.magicSquareWidgetId}-htmloutputcontainer {
    position: relative;
    min-height: 150px;
    margin: 5px 0;
    display: none;
}
#${window.magicSquareWidgetId}-pdfpngoutput {
    min-height: 120px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px;
    margin: 5px 0;
    overflow-x: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
}
#${window.magicSquareWidgetId}-pdfpreview, #${window.magicSquareWidgetId}-pngpreview {
    max-width: 100%;
    overflow-x: auto;
}
#${window.magicSquareWidgetId}-pdfpreview canvas, #${window.magicSquareWidgetId}-pngpreview canvas {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
}
.magic-square-table {
    border-collapse: collapse;
    margin: 0 auto;
    background: white;
}
.magic-square-table td {
    border: 1px solid #000;
    padding: 6px;
    text-align: center;
    vertical-align: middle;
}
.magic-square-table td span {
    display: block;
    min-width: 25px;
}
.${window.magicSquareWidgetId}-tab-content {
    height: 100%;
    overflow-y: auto;
}
#${window.magicSquareWidgetId}-htmloutput, #${window.magicSquareWidgetId}-highlightedoutput {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 10px;
    font-family: monospace;
    font-size: 12px;
    line-height: 1.5;
    white-space: pre-wrap;
    overflow: auto;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}
#${window.magicSquareWidgetId}-htmloutput {
    z-index: 2;
    background: transparent;
    color: transparent;
    caret-color: black;
    resize: none;
    outline: none;
}
#${window.magicSquareWidgetId}-highlightedoutput {
    z-index: 1;
    background: white;
    color: #333;
    pointer-events: none;
}
.tag { color: #800000; }
.string { color: #008000; }
.keyword { color: #0000ff; }
.number { color: #ff4500; }
.comment { color: #808080; font-style: italic; }
#${window.magicSquareWidgetId}-checkresult { margin-left: 10px; font-weight: normal; }
.resultiswrong { color: red; }
fieldset {
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 8px;
    margin: 8px 0;
}
fieldset legend {
    font-size: 11px;
    color: #475569;
    padding: 0 5px;
}
.${window.magicSquareWidgetId}-options-panel {
    background: #f1f5f9;
    border-radius: 6px;
    padding: 8px;
    margin-top: 5px;
}
@media (max-width: 600px) {
    .${window.magicSquareWidgetId}-modal { width: calc(100vw - 30px); right: 0; left: 0; margin: 0 auto; }
    .${window.magicSquareWidgetId}-controls { flex-direction: column; }
    .${window.magicSquareWidgetId}-formshaper-left, .${window.magicSquareWidgetId}-formshaper-right { width: 100%; }
}
        `;
        document.head.appendChild(style);
    }

    // =========================================================================
    // ARAYÜZÜ OLUŞTUR (Widget'a özgü)
    // =========================================================================
    function buildControls(container) {
        const wid = window.magicSquareWidgetId;
        var div = document.createElement('div');
        div.className = wid + '-controls';
        div.innerHTML = `
            <div class="${wid}-formshaper-left">
                <div class="${wid}-form-group">
                    <label for="${wid}-size">${mshelper.__('squareSize')}</label>
                    <input type="number" id="${wid}-size" min="3" value="3" style="width:100%;">
                </div>
                <div id="${wid}-algorithms" class="${wid}-form-group"></div>
                <div class="${wid}-form-group">
                    <label for="${wid}-rowsum">${mshelper.__('rowSum')}</label>
                    <input type="number" id="${wid}-rowsum" min="15" value="15" style="width:100%;">
                </div>
                <div class="${wid}-button-group">
                    <button id="${wid}-generate" style="width:calc(100% - 90px);">${mshelper.__('generate')}</button>
                    <section id="${wid}-lockpressed" title="${mshelper.__('titleKeepPushed')}">
                        <div class="checkbox-slider">
                            <input type="checkbox" id="${wid}-lockcreator" name="lockcreator" checked>
                            <label for="${wid}-lockcreator"></label>
                        </div>
                    </section>
                </div>
                <div class="${wid}-button-group">
                    <section id="${wid}-numberswitcher" title="${mshelper.__('titleIndianNumbers')}">
                        <div class="checkbox-slider">
                            <input type="checkbox" id="${wid}-numberswitch" name="numberswitch">
                            <label for="${wid}-numberswitch"></label>
                        </div>
                    </section>
                    <button id="${wid}-rotate">${mshelper.__('rotate')}0°</button>
                    <button id="${wid}-mirror">${mshelper.__('mirror')}</button>
                </div>
            </div>
            <div class="${wid}-formshaper-right">
                <!-- Text sekmesi için opsiyonlar -->
                <div id="${wid}-textoptions" class="${wid}-options-panel">
                    <p>${mshelper.__('tabTextDesc')}</p>
                </div>
                
                <!-- Tabled sekmesi için opsiyonlar -->
                <div id="${wid}-tabledoptions" class="${wid}-options-panel" style="display:none;">
                    <div class="${wid}-form-group">
                        <label for="${wid}-borders">${mshelper.__('boxBorders')}</label>
                        <select id="${wid}-borders" style="width:100%;">
                            <option value="0">┌──────────┘</option>
                            <option value="1">┌┄┄┄┄┄┄┄┄┄┄┘</option>
                            <option value="2">┏┅┅┅┅┅┅┅┅┅┅┛</option>
                            <option value="3">╭──────────╯</option>
                            <option value="4">┏━━━━━━━━━━┛</option>
                            <option value="5">╔══════════╝</option>
                        </select>
                    </div>
                    <div class="${wid}-form-group">
                        <label for="${wid}-cellheight">${mshelper.__('cellHeight')}</label>
                        <input type="number" id="${wid}-cellheight" min="1" value="1" style="width:100%;">
                    </div>
                    <div class="${wid}-form-group">
                        <label for="${wid}-cellwidth">${mshelper.__('cellWidth')}</label>
                        <input type="number" id="${wid}-cellwidth" min="1" value="0" style="width:100%;">
                    </div>
                </div>
                
                <!-- HTML sekmesi için opsiyonlar -->
                <div id="${wid}-htmloptions" class="${wid}-options-panel" style="display:none;">
                    <div class="${wid}-form-group">
                        <label for="${wid}-bordercolor">${mshelper.__('inkColor')}</label>
                        <input type="color" id="${wid}-bordercolor" value="#000000" style="width:100%;">
                    </div>
                    <fieldset>
                        <legend>${mshelper.__('rotationStart')}</legend>
                        <label><input type="radio" name="${wid}-rotationstart" value="left" checked> ${mshelper.__('left')}</label>
                        <label><input type="radio" name="${wid}-rotationstart" value="right"> ${mshelper.__('right')}</label>
                        <label><input type="radio" name="${wid}-rotationstart" value="none"> ${mshelper.__('none')}</label>
                    </fieldset>
                </div>
                
                <!-- PDF sekmesi için opsiyonlar -->
                <div id="${wid}-pdfoptions" class="${wid}-options-panel" style="display:none;">
                    <div class="${wid}-form-group">
                        <label for="${wid}-papersize">${mshelper.__('paperSize')}</label>
                        <select id="${wid}-papersize" style="width:100%;">
                            <option value="dream">${mshelper.__('dreamSize')}</option>
                            <option disabled>──────────────</option>
                            <option value="A5P">${mshelper.__('a5Portrait')}</option>
                            <option value="A4P">${mshelper.__('a4Portrait')}</option>
                            <option value="A3P">${mshelper.__('a3Portrait')}</option>
                            <option disabled>──────────────</option>
                            <option value="A5L">${mshelper.__('a5Landscape')}</option>
                            <option value="A4L">${mshelper.__('a4Landscape')}</option>
                            <option value="A3L">${mshelper.__('a3Landscape')}</option>
                        </select>
                    </div>
                </div>             
            </div>
        `;
        container.appendChild(div);
    }

    function buildOutputArea(container) {
        const wid = window.magicSquareWidgetId;
        var div = document.createElement('div');
        div.className = wid + '-output-area';
        div.innerHTML = `
            <div class="${wid}-form-group">
                <label>${mshelper.__('magicSquare')} <span id="${wid}-checkresult"></span></label>
                
                <!-- Text output -->
                <div id="${wid}-textoutput">
                    <textarea id="${wid}-magicsquareoutput" readonly rotated="0" flipped="false" omode="0" style="tab-size:10; width:100%; min-height:120px;"></textarea>
                </div>
                
                <!-- Tabled output -->
                <div id="${wid}-tabledoutput" style="display:none;">
                    <textarea id="${wid}-boxedoutput" readonly style="width:100%; min-height:120px;"></textarea>
                </div>
                
                <!-- HTML output -->
                <div id="${wid}-htmloutputcontainer" style="display:none; position:relative; min-height:150px;">
                    <textarea id="${wid}-htmloutput" readonly style="width:100%; height:150px; font-family:monospace;"></textarea>
                    <div id="${wid}-highlightedoutput" style="position:absolute; top:0; left:0; width:100%; height:150px; pointer-events:none; background:transparent; color:transparent; overflow:auto;"></div>
                </div>
                
                <!-- PDF/PNG output -->
                <div id="${wid}-pdfpngoutput" style="display:none;">
                    <div id="${wid}-pdfpreview"></div>
                    <div id="${wid}-pngpreview"></div>
                </div>
            </div>
            <div class="${wid}-button-group" style="margin-top:8px;">
                <button id="${wid}-copybtn" disabled>${mshelper.__('copyToClipboard')}</button>
                <button id="${wid}-savebtn" disabled>${mshelper.__('saveFile')}</button>
            </div>
        `;
        container.appendChild(div);
    }

    function buildSupportTab(container) {
        const wid = window.magicSquareWidgetId;
        var div = document.createElement('div');
        div.className = wid + '-tab-content';
        div.dataset.tab = 'support';
        div.style.display = 'none';
        div.style.margin = '0';
        div.style.padding = '0';
        div.style.overflow = 'hidden';
        div.style.height = 'calc(100% - 100px)';
        div.style.width = '100%';

        var iframe = document.createElement('iframe');
        iframe.setAttribute('id', 'bmc-iframe');
        iframe.setAttribute('allow', 'publickey-credentials-get *; payment *');
        iframe.title = 'Buy Me a Coffee';

        var widgetUrl = 'https://www.buymeacoffee.com/widget/page/' +
                        window.config.id +
                        '?description=' + encodeURIComponent(window.config.description || '') +
                        '&color=' + encodeURIComponent(window.config.color || '#FFDD00');

        iframe.src = widgetUrl;
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = '0';
        iframe.style.borderRadius = '0';
        iframe.style.background = '#fff';
        iframe.style.backgroundImage = 'url(https://cdn.buymeacoffee.com/assets/img/widget/loader.svg)';
        iframe.style.backgroundPosition = 'center';
        iframe.style.backgroundSize = '64px';
        iframe.style.backgroundRepeat = 'no-repeat';
        iframe.style.overflow = 'hidden';
        iframe.style.display = 'block';
        iframe.style.margin = '0';
        iframe.style.padding = '0';
        
        var socialDiv = document.createElement('div');
        socialDiv.className = 'bmc-social-footer';
        socialDiv.style.margin = '0';
        socialDiv.style.padding = '15px 0';
        socialDiv.style.borderTop = '1px solid #e1e1e1';
        socialDiv.style.textAlign = 'center';
        socialDiv.style.backgroundColor = '#fff';
        socialDiv.style.width = '100%';
        socialDiv.style.boxSizing = 'border-box';
        
        socialDiv.innerHTML = `
            <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">${mshelper.__('bmcNote') || 'Follow me on social media'}</p>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin: 0; padding: 0;">
                <a href="https://facebook.com/${window.config.id}" target="_blank" style="text-decoration: none; font-size: 24px; transition: transform 0.25s ease; display: inline-block;">👥</a>
                <a href="https://instagram.com/${window.config.id}" target="_blank" style="text-decoration: none; font-size: 24px; transition: transform 0.25s ease; display: inline-block;">📷</a>
                <a href="https://github.com/${window.config.id}" target="_blank" style="text-decoration: none; font-size: 24px; transition: transform 0.25s ease; display: inline-block;">🐙</a>
                <a href="https://thingiverse.com/${window.config.id}" target="_blank" style="text-decoration: none; font-size: 24px; transition: transform 0.25s ease; display: inline-block;">🤖</a>
                <a href="https://one.fanclub.rocks/" target="_blank" style="text-decoration: none; font-size: 24px; transition: transform 0.25s ease; display: inline-block;">☀️</a>
            </div>
        `;
        
        div.appendChild(iframe);

        iframe.addEventListener('load', function() {
            this.style.backgroundImage = 'none';
        });
        iframe.style.opacity = '0';
        iframe.style.transition = 'opacity 0.25s ease';
        setTimeout(() => { iframe.style.opacity = '1'; }, 100);
        
        container.appendChild(div);        
        container.appendChild(socialDiv);
    }

    // =========================================================================
    // DOM YAPISINI OLUŞTUR (Widget'a özgü)
    // =========================================================================
    const wid = window.magicSquareWidgetId;
    
    var container = document.createElement('div');
    container.className = wid + '-container';
    container.id = wid + '-container';

    var button = document.createElement('div');
    button.className = wid + '-button';
    button.innerHTML = window.config.button_content;
    container.appendChild(button);

    var modal = document.createElement('div');
    modal.className = wid + '-modal';
    modal.id = wid + '-modal';

    var tabsDiv = document.createElement('div');
    tabsDiv.className = wid + '-tabs';
    var tabs = ['text', 'tabled', 'html', 'pdf', 'png', 'support'];
    var tabLabels = {
        'text': mshelper.__('tabText'),
        'tabled': mshelper.__('tabTabled'),
        'html': mshelper.__('tabHtml'),
        'pdf': mshelper.__('tabPdf'),
        'png': mshelper.__('tabPng'),
        'support': mshelper.__('tabSupport')
    };

    tabs.forEach(function(t) {
        var tab = document.createElement('div');
        tab.className = wid + '-tab';
        tab.dataset.tab = t;
        tab.textContent = tabLabels[t];
        tab.addEventListener('click', function() { switchTab(t); });
        tabsDiv.appendChild(tab);
    });
    modal.appendChild(tabsDiv);

    var contentDiv = document.createElement('div');
    contentDiv.className = wid + '-content';
    contentDiv.id = wid + '-content';
    modal.appendChild(contentDiv);
    container.appendChild(modal);
    document.body.appendChild(container);

    // Ana içeriği oluştur
    buildControls(contentDiv);
    buildOutputArea(contentDiv);
    
    // Algoritma seçiciyi doldur (LUX dahil)
    var algorithmsDiv = document.getElementById(wid + '-algorithms');
    if (algorithmsDiv) {
        algorithmsDiv.innerHTML = createAlgorithmSelect(3);
    }

    // Destek sekmesini ekle
    buildSupportTab(contentDiv);

    // =========================================================================
    // MODAL TOGGLE (Widget'a özgü)
    // =========================================================================
    button.addEventListener('click', function(e) {
        e.stopPropagation();
        var disp = modal.style.display;
        modal.style.display = disp === 'flex' ? 'none' : 'flex';
        if (modal.style.display === 'flex' && typeof window.generateMagicSquare === 'function') {
            window.generateMagicSquare('check');
        }
    });

    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            modal.style.display = 'none';
        }
    });

    // =========================================================================
    // EVENT LISTENER'LAR (Widget'a özgü)
    // =========================================================================
    
    const generateBtn = document.getElementById(wid + '-generate');
    if (generateBtn && typeof window.generateMagicSquare === 'function') {
        generateBtn.addEventListener('click', function() {
            window.generateMagicSquare('check');
        });
    }

    const rotateBtn = document.getElementById(wid + '-rotate');
    if (rotateBtn && typeof window.rotateTheSquare === 'function') {
        rotateBtn.addEventListener('click', function() {
            window.rotateTheSquare();
        });
    }

    const mirrorBtn = document.getElementById(wid + '-mirror');
    if (mirrorBtn && typeof window.mirrorTheSquare === 'function') {
        mirrorBtn.addEventListener('click', function() {
            window.mirrorTheSquare();
        });
    }

    const copyBtn = document.getElementById(wid + '-copybtn');
    const saveBtn = document.getElementById(wid + '-savebtn');

    if (copyBtn) {
        copyBtn.addEventListener('click', window.copyToClipboard);
    }
    if (saveBtn) {
        saveBtn.addEventListener('click', window.saveToLocalDisk);
    }

    // Lock creator
    const lockCreator = document.getElementById(wid + '-lockcreator');
    if (lockCreator) {
        lockCreator.addEventListener('change', function() {
            const genBtn = document.getElementById(wid + '-generate');
            if (this.checked) {
                genBtn.disabled = true;
                if (typeof window.generateMagicSquare === 'function') {
                    window.generateMagicSquare('check');
                }
            } else {
                genBtn.disabled = false;
            }
        });
    }

    // Tüm input değişimlerinde generate'i çağır
    const inputIds = ['size', 'rowsum', 'algorithm', 'borders', 'cellheight', 'cellwidth', 
                      'bordercolor', 'papersize', 'numberswitch'];
    
    inputIds.forEach(function(id) {
        const el = document.getElementById(wid + '-' + id);
        if (el) {
            el.addEventListener('change', function() {
                if (lockCreator && lockCreator.checked && typeof window.generateMagicSquare === 'function') {
                    window.generateMagicSquare('check');
                }
            });
        }
    });

    // Radyo butonları
    const radios = document.querySelectorAll('input[name="' + wid + '-rotationstart"]');
    radios.forEach(function(r) {
        r.addEventListener('change', function() {
            if (lockCreator && lockCreator.checked && typeof window.generateMagicSquare === 'function') {
                window.generateMagicSquare('check');
            }
        });
    });

    // Size değiştiğinde algoritma seçicini güncelle
    const sizeEl = mshelper.getElement('size');
    if (sizeEl) {
        sizeEl.addEventListener('change', function() {
            const n = parseInt(this.value);
            const magicConstant = (n * (n * n + 1)) / 2;
            const rowSumEl = document.getElementById(wid + '-rowsum');
            
            if (rowSumEl) {
                const currentSum = parseFloat(rowSumEl.value);
                if (currentSum <= magicConstant) {
                    rowSumEl.value = magicConstant;
                }
                rowSumEl.setAttribute('min', magicConstant);
            }
            
            const algorithmsDiv = document.getElementById(wid + '-algorithms');
            if (algorithmsDiv) {
                algorithmsDiv.innerHTML = createAlgorithmSelect(n);
                window.generateMagicSquare('check');
            }
        });
    }
    
    // Row sum min değerini ayarla
    const rowSumEl = document.getElementById(wid + '-rowsum');
    if (rowSumEl && sizeEl) {
        const n = parseInt(sizeEl.value);
        const magicConstant = (n * (n * n + 1)) / 2;
        rowSumEl.setAttribute('min', magicConstant);
    }

    // HTML highlight scroll senkronizasyonu
    const htmlOutput = document.getElementById(wid + '-htmloutput');
    const highlighted = document.getElementById(wid + '-highlightedoutput');
    if (htmlOutput && highlighted) {
        htmlOutput.addEventListener('scroll', function() {
            highlighted.scrollTop = this.scrollTop;
        });
    }

    // =========================================================================
    // İLK SEKMELERİ AYARLA
    // =========================================================================
    setTimeout(function() {
        switchTab('text');
        if (typeof window.generateMagicSquare === 'function') {
            window.generateMagicSquare('check');
        }
    }, 200);

    // =========================================================================
    // SAYI DÖNÜŞÜM FONKSİYONLARI (offline'dan)
    // =========================================================================
    window.IndianToArab = function(number) {
        return String(number)
            .replace(/٠/g, '0').replace(/١/g, '1').replace(/٢/g, '2').replace(/٣/g, '3')
            .replace(/٤/g, '4').replace(/٥/g, '5').replace(/٦/g, '6').replace(/٧/g, '7')
            .replace(/٨/g, '8').replace(/٩/g, '9');
    };

    window.ArabToIndian = function(number) {
        return String(number)
            .replace(/0/g, '٠').replace(/1/g, '١').replace(/2/g, '٢').replace(/3/g, '٣')
            .replace(/4/g, '٤').replace(/5/g, '٥').replace(/6/g, '٦').replace(/7/g, '٧')
            .replace(/8/g, '٨').replace(/9/g, '٩');
    };

    // =========================================================================
    // SİHİRLİ KARE OLUŞTURMA ALGORİTMALARI - OFFLINE'DAN BİREBİR
    // =========================================================================

    // Tek boyutlu sihirli kareler için algoritmalar
    window.siameseMethod = function(n) {
        let MagicSquare = new Array(n).fill(0).map(() => new Array(n).fill(0));
        let row = 0, col = Math.floor(n / 2);
        for (let num = 1; num <= n * n; num++) {
            MagicSquare[row][col] = num;
            let nextRow = (row - 1 + n) % n;
            let nextCol = (col + 1) % n;
            if (MagicSquare[nextRow][nextCol] !== 0) {
                row = (row + 1) % n;
            } else {
                row = nextRow;
                col = nextCol;
            }
        }
        return MagicSquare;
    };
    
    // 4'ün katı olan çift boyutlu sihirli kare (Doubly Even) algoritmalar
    window.stracheyMethod = function(n) {
        let MagicSquare = new Array(n).fill(0).map(() => new Array(n).fill(0));
        let count = 1;
        for (let i = 0; i < n; i++) {
            for (let j = 0; j < n; j++) {
                if ((i % 4 === j % 4) || ((i + j) % 4 === 3)) {
                    MagicSquare[i][j] = (n * n) - count + 1;
                } else {
                    MagicSquare[i][j] = count;
                }
                count++;
            }
        }
        return MagicSquare;
    };
    
    window.durerMethod = function(n) {
        let MagicSquare = new Array(n).fill(0).map(() => new Array(n).fill(0));
        let count = 1;
        for (let i = 0; i < n; i++) {
            for (let j = 0; j < n; j++) {
                if ((i % 4 === 0 || i % 4 === 3) && (j % 4 === 0 || j % 4 === 3)) {
                    MagicSquare[i][j] = count;
                } else if ((i % 4 === 1 || i % 4 === 2) && (j % 4 === 1 || j % 4 === 2)) {
                    MagicSquare[i][j] = count;
                } else {
                    MagicSquare[i][j] = (n * n) - count + 1;
                }
                count++;
            }
        }
        return MagicSquare;
    };

    window.simpleExchangeMethod = function(n) {
        let MagicSquare = new Array(n).fill(0).map(() => new Array(n).fill(0));
        let count = 1;
        for (let i = 0; i < n; i++) {
            for (let j = 0; j < n; j++) {
                MagicSquare[i][j] = count++;
            }
        }
        for (let i = 0; i < n; i++) {
            for (let j = 0; j < n; j++) {
                if ((i % 4 === j % 4) || ((i + j) % 4 === 3)) {
                    MagicSquare[i][j] = (n * n) - MagicSquare[i][j] + 1;
                }
            }
        }
        return MagicSquare;
    };

    // LUX Metodu (Singular Even için Conway'un metodu)
    window.abdilLUXMethod = function(n) {
        // Sadece n = 4k+2 için çalışır
        if (n % 4 !== 2) {
            throw new Error("LUX method only works for singly even orders (multiple of 4 plus 2)");
        }
        const k = (n - 2) / 4;
        const size = 2 * k + 1;
        let MagicSquare = new Array(n).fill(0).map(() => new Array(n).fill(0));
        let lux = new Array(size).fill(0).map(() => new Array(size).fill(''));

        // LUX matrisini oluştur
        for (let i = 0; i < size; i++) {
            for (let j = 0; j < size; j++) {
                if (i < k) lux[i][j] = 'L';
                else if (i === k) lux[i][j] = 'U';
                else if (i === k + 1) lux[i][j] = 'X';
                else lux[i][j] = 'L';
            }
        }
        // Ortadaki X'in altındaki satırın ortasındaki hücreyi U yap
        lux[k+1][k] = 'U';

        // Temel sihirli kare oluştur (Siamese)
        let base = siameseMethod(size);
        
        // LUX matrisine göre yerleştir
        for (let i = 0; i < size; i++) {
            for (let j = 0; j < size; j++) {
                let val = base[i][j] - 1; // 0-based
                let quadRowStart = i * 2;
                let quadColStart = j * 2;
                
                if (lux[i][j] === 'L') {
                    MagicSquare[quadRowStart][quadColStart] = 4*val + 1;
                    MagicSquare[quadRowStart][quadColStart+1] = 4*val + 2;
                    MagicSquare[quadRowStart+1][quadColStart] = 4*val + 3;
                    MagicSquare[quadRowStart+1][quadColStart+1] = 4*val + 4;
                } else if (lux[i][j] === 'U') {
                    MagicSquare[quadRowStart][quadColStart] = 4*val + 1;
                    MagicSquare[quadRowStart][quadColStart+1] = 4*val + 4;
                    MagicSquare[quadRowStart+1][quadColStart] = 4*val + 2;
                    MagicSquare[quadRowStart+1][quadColStart+1] = 4*val + 3;
                } else if (lux[i][j] === 'X') {
                    MagicSquare[quadRowStart][quadColStart] = 4*val + 1;
                    MagicSquare[quadRowStart][quadColStart+1] = 4*val + 3;
                    MagicSquare[quadRowStart+1][quadColStart] = 4*val + 2;
                    MagicSquare[quadRowStart+1][quadColStart+1] = 4*val + 4;
                }
            }
        }
        
        // Merkezdeki alt karede değişim (4x4)
        let temp = MagicSquare[n/2][n/2-1];
        MagicSquare[n/2][n/2-1] = MagicSquare[n/2+1][n/2-1];
        MagicSquare[n/2+1][n/2-1] = temp;
        
        return MagicSquare;
    };

    // 4'ün katı olmayan çift boyutlu sihirli kare (Singly Even) algoritması
    window.stracheySinglyEvenMethod = function(n) {
        const wid = window.magicSquareWidgetId;
        let MagicSquare = Array.from({ length: n }, () => Array(n).fill(0));
        const k = n / 2;
        let miniMagic = window.siameseMethod(k);
        
        const MagicConstant = (n * (n * n + 1)) / 2;
        const expectedRowElement = document.getElementById(wid + '-rowsum');
        const RowSum = parseFloat(expectedRowElement.value);
        
        if (!(RowSum <= MagicConstant || RowSum % 2 == 0)) {
            miniMagic = window.incrementedMagicSquare(miniMagic, (RowSum - (3 * k * k * k)) / 2);
        }
        
        for (let i = 0; i < k; i++) {
            for (let j = 0; j < k; j++) {
                MagicSquare[i][j] = miniMagic[i][j];
                MagicSquare[i + k][j + k] = miniMagic[i][j] + k * k;
                MagicSquare[i][j + k] = miniMagic[i][j] + 2 * k * k;
                MagicSquare[i + k][j] = miniMagic[i][j] + 3 * k * k;
            }
        }
        
        const swapCol = [];
        const swapCount = (k - 1) / 2;
        for (let i = 0; i < swapCount; i++) swapCol.push(i);
        for (let i = n - swapCount + 1; i < n; i++) swapCol.push(i);
        
        for (let i = 0; i < k; i++) {
            for (let j = 0; j < swapCol.length; j++) {
                const col = swapCol[j];
                [MagicSquare[i][col], MagicSquare[i + k][col]] = [MagicSquare[i + k][col], MagicSquare[i][col]];
            }
        }
        
        const halfK = Math.floor(k / 2);
        [MagicSquare[halfK][0], MagicSquare[halfK + k][0]] = [MagicSquare[halfK + k][0], MagicSquare[halfK][0]];
        [MagicSquare[halfK + k][halfK], MagicSquare[halfK][halfK]] = [MagicSquare[halfK][halfK], MagicSquare[halfK + k][halfK]];
        
        if (!(RowSum <= MagicConstant || RowSum % 2 != 0)) {
            MagicSquare = window.incrementMatrix(MagicSquare, RowSum);
        }
        
        return MagicSquare;
    };
    
    // =========================================================================
    // ALGORİTMA SEÇİCİYİ OLUŞTUR (4'ün katı için varsayılan Dürer yapalım)
    // =========================================================================
    function createAlgorithmSelect(n) {
        const wid = window.magicSquareWidgetId;
        var html = `<select id="${wid}-algorithm" style="width:100%;">`;
        if (n % 2 === 1) {
            html += `<option selected value="siamese">${mshelper.__('siamese')}</option>`;
        } else if (n % 4 === 0) {
            html += `<option value="stracheyDouble">${mshelper.__('stracheyDouble')}</option>`;
            html += `<option selected value="durer">${mshelper.__('durer')}</option>`;
            html += `<option value="simpleExchange">${mshelper.__('simpleExchange')}</option>`;
        } else {
            html += `<option selected value="stracheySingle">${mshelper.__('stracheySingle')}</option>`;
        }
        html += `</select>`;
        return html;
    }
    
    window.createMagicSquare = function(n) {
        const wid = window.magicSquareWidgetId;
        let algorithm = document.getElementById(wid + '-algorithm').value;
        if (algorithm == 'siamese') {
            return window.siameseMethod(n);
        } else if (algorithm == 'stracheyDouble') {
            return window.stracheyMethod(n);
        } else if (algorithm == 'durer') {
            return window.durerMethod(n);
        } else if (algorithm == 'simpleExchange') {
            return window.simpleExchangeMethod(n);
        } else if (algorithm == 'stracheySingle') {
            return window.stracheySinglyEvenMethod(n);
        }
    };

    // =========================================================================
    // MATRİS ARTIRIM (INCREMENT) FONKSİYONLARI
    // =========================================================================

    // Hücre bazında artırım miktarını belirleyen yardımcı fonksiyon
    window.incrementionForCell = function(n, RowSum, incremention, cellvalue) {
        if (cellvalue > (n * n) - (n * (RowSum % n))) {
            return Math.ceil(incremention);
        } else {
            return Math.floor(incremention);
        }
    };

    // Tek sayı boyutlu kareler için artırım (Siamese tabanlı)
    window.incrementedMagicSquare = function(MagicSquare, RowSum) {
        const n = MagicSquare.length;
        const MagicConstant = (n * (n * n + 1)) / 2;
        const incremention = (RowSum - MagicConstant) / n;
        for (let r = 0; r < n; r++) {
            for (let c = 0; c < n; c++) {
                MagicSquare[r][c] += window.incrementionForCell(n, RowSum, incremention, MagicSquare[r][c]);
            }
        }
        return MagicSquare;
    };

    // Çift boyutlu kareler için artırım (özel algoritma)
    window.incrementMatrix = function(MagicSquare, RowSum) {
        const n = MagicSquare.length;
        const MagicConstant = (n * (n * n + 1)) / 2;
        const diff = RowSum - MagicConstant;
        const z = diff % n;
        const increment = (diff - z) / n;
        
        // Desen bazlı artırma (orijinal sıra)
        for (let k = 0; k < z; k++) {
            for (let i = 0; i < n; i++) {
                const row = (k + i) % n;
                const col = i;
                MagicSquare[row][col]++;
            }
        }
        
        // Taban artırımı
        for (let r = 0; r < n; r++) {
            for (let c = 0; c < n; c++) {
                MagicSquare[r][c] += increment;
            }
        }
        return MagicSquare;
    };

    // =========================================================================
    // DÖNÜŞTÜRME VE YANSITMA FONKSİYONLARI
    // =========================================================================

    window.rotateMatrix = function(matrix, repeat) {
        let n = matrix.length;
        let rotated = new Array(n).fill(0).map(() => new Array(n).fill(0));
        for (let times = 0; times < repeat; times++) {
            for (let i = 0; i < n / 2; i++) {
                for (let j = i; j < n - i - 1; j++) {
                    let temp = matrix[i][j];
                    matrix[i][j] = matrix[j][n - i - 1];
                    matrix[j][n - i - 1] = matrix[n - i - 1][n - j - 1];
                    matrix[n - i - 1][n - j - 1] = matrix[n - j - 1][i];
                    matrix[n - j - 1][i] = temp;
                }
            }
            rotated = matrix;
            matrix = rotated;
        }
        return rotated;
    };

    window.mirrorFlip = function(MagicSquare) {
        const N = MagicSquare.length;
        let mirrorFlip = new Array(N).fill(0).map(() => new Array(N).fill(0));
        for (let a = 0; a < N; a++) {
            for (let b = 0; b < N; b++) {
                let m = N - 1 - a;
                let n = N - 1 - b;
                mirrorFlip[a][b] = MagicSquare[m][n];
            }
        }
        return mirrorFlip;
    };

    window.mirrorTheSquare = function() {
        const wid = window.magicSquareWidgetId;
        let flipped = document.getElementById(wid + '-magicsquareoutput').getAttribute('flipped');
        if (flipped == "true") {
            document.getElementById(wid + '-magicsquareoutput').setAttribute('flipped', "false");
        } else {
            document.getElementById(wid + '-magicsquareoutput').setAttribute('flipped', "true");
        }
        window.generateMagicSquare("flip");
    };

    window.rotateTheSquare = function() {
        const wid = window.magicSquareWidgetId;
        let previousrotation = parseFloat(document.getElementById(wid + '-magicsquareoutput').getAttribute('rotated') || '0');
        let degrees = previousrotation;
        if (previousrotation == 270) {
            degrees = 0;
        } else {
            degrees += 90;
        }
        document.getElementById(wid + '-magicsquareoutput').setAttribute('rotated', degrees);
        document.getElementById(wid + '-rotate').innerHTML = mshelper.__('rotate') + degrees + '°';
        window.generateMagicSquare("rotate");
    };

    // =========================================================================
    // FORMATLAMA FONKSİYONLARI
    // =========================================================================
    window.formatMagicSquare = function(MagicSquare) {
        return MagicSquare.map(row => row.join('\t')).join('\n');
    };

    window.boxTheSquare = function(MagicSquare) {
        const box = ["─│┌┐└┘├┼┤┬┴", "┄┆┌┐└┘├┼┤┬┴", "┅┇┏┓┗┛┣╋┫┳┻", "─│╭╮╰╯├┼┤┬┴", "━┃┏┓┗┛┣╋┫┳┻", "═║╔╗╚╝╠╬╣╦╩"];
        const wid = window.magicSquareWidgetId;
        
        $("#" + wid + "-cellheight")[0].setAttribute('min', "1");
        
        const xob = [
            parseFloat(document.getElementById(wid + '-borders').value),
            parseFloat(document.getElementById(wid + '-cellheight').value),
            parseFloat(document.getElementById(wid + '-cellwidth').value)
        ];
        
        const n = MagicSquare.length;
        let boxed = "";
        
        let longestlength = 0;
        let lengthofcell = 0;
        let lengthfornum = 0;
        let borderlength = 0;
        
        if (document.getElementById(wid + '-numberswitch') && 
            document.getElementById(wid + '-numberswitch').checked) {
            longestlength += 3;
        }
        
        for (let r = 0; r < n; r++) {
            for (let c = 0; c < n; c++) {
                lengthofcell = String(MagicSquare[r][c]).length;
                if (lengthofcell > longestlength) {
                    longestlength = lengthofcell;
                }
            }
        }
        
        if (document.getElementById(wid + '-numberswitch') && 
            document.getElementById(wid + '-numberswitch').checked) {
            $("#" + wid + "-cellwidth")[0].setAttribute('min', String(longestlength - 3));
            if (xob[2] + 3 > longestlength) {
                longestlength = xob[2] + 3;
            }
            borderlength = longestlength - 3;
            lengthfornum = longestlength;
        } else {
            $("#" + wid + "-cellwidth")[0].setAttribute('min', String(longestlength));
            if (xob[2] > longestlength) {
                longestlength = xob[2];
            }
            borderlength = longestlength;
            lengthfornum = longestlength;
        }
        
        let centering = true;
        let bottomborder = false;
        
        for (let r = 0; r < n; r++) {
            if (r == 0) {
                boxed += box[xob[0]][2];
                for (let t = 0; t < n - 1; t++) {
                    for (let x = 0; x < borderlength; x++) boxed += box[xob[0]][0];
                    boxed += box[xob[0]][9];
                }
                for (let x = 0; x < borderlength; x++) boxed += box[xob[0]][0];
                boxed += box[xob[0]][3] + "\n";
            }
            
            for (let e = 0; e < Math.floor((xob[1] - 1) / 2); e++) {
                boxed += box[xob[0]][1];
                for (let c = 0; c < n; c++) {
                    for (let s = 0; s < borderlength; s++) boxed += " ";
                    boxed += box[xob[0]][1];
                }
                boxed += "\n";
            }
            
            boxed += box[xob[0]][1];
            for (let c = 0; c < n; c++) {
                let cellvalue = String(MagicSquare[r][c]);
                if (cellvalue.length < lengthfornum) {
                    for (let s = 0; s < (lengthfornum - String(MagicSquare[r][c]).length); s++) {
                        if (centering) {
                            cellvalue = cellvalue + " ";
                            centering = false;
                        } else {
                            cellvalue = " " + cellvalue;
                            centering = true;
                        }
                    }
                }
                centering = true;
                boxed += cellvalue;
                boxed += box[xob[0]][1];
            }
            boxed += "\n";
            
            for (let e = 0; e < ((xob[1] - 1) - Math.floor((xob[1] - 1) / 2)); e++) {
                boxed += box[xob[0]][1];
                for (let c = 0; c < n; c++) {
                    for (let s = 0; s < borderlength; s++) boxed += " ";
                    boxed += box[xob[0]][1];
                }
                boxed += "\n";
            }
            
            if (r >= 0) {
                bottomborder = false;
                if (r < n - 1) {
                    boxed += box[xob[0]][6];
                    for (let t = 0; t < n - 1; t++) {
                        for (let x = 0; x < borderlength; x++) boxed += box[xob[0]][0];
                        boxed += box[xob[0]][7];
                    }
                    for (let x = 0; x < borderlength; x++) boxed += box[xob[0]][0];
                    boxed += box[xob[0]][8] + "\n";
                } else {
                    bottomborder = true;
                }
            }
            
            if (bottomborder) {
                boxed += box[xob[0]][4];
                for (let t = 0; t < n - 1; t++) {
                    for (let x = 0; x < borderlength; x++) boxed += box[xob[0]][0];
                    boxed += box[xob[0]][10];
                }
                for (let x = 0; x < borderlength; x++) boxed += box[xob[0]][0];
                boxed += box[xob[0]][5] + "\n";
            }
        }
        return boxed;
    };

    window.createHTML = function(HtmlHolder, MagicSquare) {
        const n = MagicSquare.length;
        const wid = window.magicSquareWidgetId;
        const expectedRowElement = document.getElementById(wid + '-rowsum');
        const holder = document.createElement('div');
        holder.setAttribute('id', wid + '-htmlholder');
        
        const html = document.createElement('html');
        html.setAttribute('lang', 'en');
        
        const head = document.createElement('head');
        const metaCharset = document.createElement('meta');
        metaCharset.setAttribute('charset', 'UTF-8');
        
        const metaViewport = document.createElement('meta');
        metaViewport.setAttribute('name', 'viewport');
        metaViewport.setAttribute('content', 'width=device-width, initial-scale=1.0');
        
        const title = document.createElement('title');
        let titletext = "";
        
        const useIndian = document.getElementById(wid + '-numberswitch') && 
                          document.getElementById(wid + '-numberswitch').checked;
        
        titletext = n + "x" + n + " Magic Square " + (useIndian ? "(Indian Numbers)" : "(Arabic Numbers)");
        title.textContent = titletext;
        
        const style = document.createElement('style');
        style.textContent = window.generateTableStyles();
        
        const script = document.createElement('script');
        script.textContent = `
            function equalizeCells_${wid.replace(/-/g, '_')}() {
                const cells = document.querySelectorAll('.magic-square-table td');
                let maxWidthCell = 0, maxHeightCell = 0;
                cells.forEach(cell => {
                    const span = cell.querySelector('span');
                    if (span) {
                        span.style.display = 'table-cell';
                        span.style.whiteSpace = 'nowrap';
                    }
                });
                document.body.offsetHeight;
                cells.forEach(cell => {
                    const span = cell.querySelector('span');
                    if (span) {
                        maxWidthCell = Math.max(maxWidthCell, span.offsetWidth);
                        maxHeightCell = Math.max(maxHeightCell, span.offsetHeight);
                    }
                });
                const style = document.createElement('style');
                style.textContent = \`
                    .magic-square-table td {
                        aspect-ratio: 1 / 1;
                        width: max-content !important;
                        height: max-content !important;
                    }
                    .magic-square-table td span {
                        aspect-ratio: 1 / 1;
                        width: \${maxWidthCell}px !important;
                        height: \${maxHeightCell}px !important;
                    }
                \`;
                document.head.appendChild(style);
            }
            document.addEventListener('DOMContentLoaded', function() {
                equalizeCells_${wid.replace(/-/g, '_')}();
            });
        `;
        
        head.appendChild(metaCharset);
        head.appendChild(metaViewport);
        head.appendChild(title);
        head.appendChild(style);
        head.appendChild(script);
        
        const body = document.createElement('body');
        body.style.backgroundColor = "white";
        
        const header = document.createElement('header');
        const h1 = document.createElement('h1');
        h1.textContent = titletext;
        
        const main = document.createElement('main');
        const p = document.createElement('p');
        
        let firstparagraph = `Row sums and column sums are all equal to ${expectedRowElement.value}`;
        p.textContent = firstparagraph;
        
        const squarecontainer = document.createElement('div');
        squarecontainer.setAttribute('id', wid + '-themagicsquare');
        
        const footer = document.createElement('footer');
        const pFooter = document.createElement('p');
        pFooter.textContent = '2026 © https://one.fanclub.rocks/';
        
        header.appendChild(h1);
        main.appendChild(p);
        main.appendChild(squarecontainer);
        footer.appendChild(pFooter);
        body.appendChild(header);
        body.appendChild(main);
        body.appendChild(footer);
        
        html.appendChild(head);
        html.appendChild(body);
        holder.appendChild(html);
        
        const htmlcontainer = document.getElementById(HtmlHolder);
        if (htmlcontainer) {
            htmlcontainer.innerHTML = "";
            htmlcontainer.appendChild(holder);
        }
        
        window.renderMagicSquareToTable(MagicSquare, wid + '-themagicsquare');
        return window.formatHTML(holder.innerHTML);
    };

    window.renderMagicSquareToTable = function(MagicSquare, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        const table = document.createElement('table');
        table.classList.add('magic-square-table');
        
        MagicSquare.forEach((row, rowIndex) => {
            const tr = document.createElement('tr');
            row.forEach((cellValue, colIndex) => {
                const td = document.createElement('td');
                const span = document.createElement('span');
                td.appendChild(span);
                span.textContent = cellValue;
                
                td.classList.add(`row-${rowIndex % 2 === 0 ? 'even' : 'odd'}`, 
                                `col-${colIndex % 2 === 0 ? 'even' : 'odd'}`);
                td.setAttribute('data-value', cellValue);
                tr.appendChild(td);
            });
            table.appendChild(tr);
        });
        
        container.appendChild(table);
    };

    window.generateTableStyles = function() {
        const wid = window.magicSquareWidgetId;
        const borderColor = document.getElementById(wid + '-bordercolor') ? 
                            document.getElementById(wid + '-bordercolor').value : '#000000';
        
        const startDirectionRadio = document.querySelector('input[name="' + wid + '-rotationstart"]:checked');
        const startDirection = startDirectionRadio ? startDirectionRadio.value : 'none';
        
        const baseRotate = startDirection === 'left' ? '-45deg' : '45deg';
        const oppositeRotate = startDirection === 'left' ? '45deg' : '-45deg';
        
        if (startDirection != 'none') {
            return `
                table.magic-square-table {
                    border-collapse: collapse;
                    table-layout: fixed;
                    width: max-content;
                }
                table.magic-square-table, table.magic-square-table td {
                    font-size: 1em;
                    font-family: Arial;
                    font-weight: bold;
                    color: ${borderColor};
                    border: 1px solid ${borderColor};
                }
                .magic-square-table td {
                    padding: 8px;
                    text-align: center;
                    vertical-align: middle;
                    box-sizing: border-box;
                }
                .magic-square-table td > span {
                    display: table-cell;
                    text-align: center;
                    vertical-align: middle;
                    white-space: nowrap;
                }
                .row-even.col-even > span { transform: rotate(${oppositeRotate}); }
                .row-even.col-odd > span { transform: rotate(${baseRotate}); }
                .row-odd.col-even > span { transform: rotate(${baseRotate}); }
                .row-odd.col-odd > span { transform: rotate(${oppositeRotate}); }
            `;
        } else {
            return `
                table.magic-square-table {
                    border-collapse: collapse;
                    table-layout: fixed;
                    width: max-content;
                }
                table.magic-square-table, table.magic-square-table td {
                    color: ${borderColor};
                    border: 1px solid ${borderColor};
                }
                .magic-square-table td {
                    width: max-content;
                    padding: 8px;
                    text-align: center;
                    vertical-align: middle;
                }
                .magic-square-table td > span {
                    display: table-cell;
                    width: max-content;
                    text-align: center;
                    vertical-align: middle;
                }
            `;
        }
    };

    window.preEqualizeCells = function() {
        const wid = window.magicSquareWidgetId;
        let previousstyle = document.getElementById('pageofpdf-' + wid);
        const cells = document.querySelectorAll('.magic-square-table td');
        let maxWidthCell = 0, maxHeightCell = 0;
        
        cells.forEach(cell => {
            const span = cell.querySelector('span');
            if (span) {
                span.style.display = 'table-cell';
                span.style.whiteSpace = 'nowrap';
            }
        });
        
        document.body.offsetHeight;
        
        cells.forEach(cell => {
            const span = cell.querySelector('span');
            if (span) {
                maxWidthCell = Math.max(maxWidthCell, span.offsetWidth);
                maxHeightCell = Math.max(maxHeightCell, span.offsetHeight);
            }
        });
        
        const style = document.createElement('style');
        style.textContent = `
            .magic-square-table td {
                aspect-ratio: 1 / 1;
                width: max-content !important;
                height: max-content !important;
            }
            .magic-square-table td span {
                aspect-ratio: 1 / 1;
                width: ${maxWidthCell}px !important;
                height: ${maxHeightCell}px !important;
            }
        `;
        
        if (previousstyle) previousstyle.remove();
        style.setAttribute('id', 'pageofpdf-' + wid);
        document.head.appendChild(style);
    };

    window.highlightCode = function(input, output) {
        if (!input || !output) return;
        const code = input.value;
        const highlighted = code
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"(.*?)"/g, '<span class="string">"$1"</span>')
            .replace(/&lt;(\w+)(.*?)&gt;/g, '<span class="tag">&lt;$1$2&gt;</span>')
            .replace(/&lt;\/(\w+)&gt;/g, '<span class="tag">&lt;/$1&gt;</span>');
        output.innerHTML = highlighted;
    };

    window.formatHTML = function(html) {
        const tab = '\t';
        let result = '';
        let indentLevel = 0;
        
        html.split(/(<[^>]+>)/).forEach((element) => {
            if (!element.trim()) return;
            
            if (element.startsWith('</')) {
                indentLevel--;
                result += tab.repeat(indentLevel) + element + '\n';
            } else if (element.startsWith('<') && !element.startsWith('<!')) {
                if (element.endsWith('/>') || window.isSelfClosingTag(element)) {
                    result += tab.repeat(indentLevel) + element + '\n';
                } else {
                    result += tab.repeat(indentLevel) + element + '\n';
                    indentLevel++;
                }
            } else if (element.startsWith('<!')) {
                result += tab.repeat(indentLevel) + element + '\n';
            } else {
                result += tab.repeat(indentLevel) + element + '\n';
            }
        });
        return result.trim();
    };

    window.isSelfClosingTag = function(tag) {
        const selfClosingTags = ['meta', 'img', 'br', 'hr', 'input', 'link', 'area', 'base', 
                                 'col', 'command', 'embed', 'keygen', 'param', 'source', 'track', 'wbr'];
        const tagName = tag.match(/<([^\s/>]+)/)?.[1];
        return tagName && selfClosingTags.includes(tagName.toLowerCase());
    };

    // =========================================================================
    // ANA SİHİRLİ KARE OLUŞTURMA FONKSİYONU
    // =========================================================================
    window.generateMagicSquare = function(job) {
        const wid = window.magicSquareWidgetId;
        const n = parseInt(mshelper.getElement('size').value);
        const r = parseFloat(document.getElementById(wid + '-magicsquareoutput').getAttribute('rotated') || '0');
        const f = document.getElementById(wid + '-magicsquareoutput').getAttribute('flipped');
        const expectedRowElement = document.getElementById(wid + '-rowsum');
        
        if (n < 3) {
            alert("Lütfen 3 veya daha büyük bir sayı girin.");
            return;
        }
        
        const MagicConstant = (n * (n * n + 1)) / 2;
        const RowSum = parseFloat(expectedRowElement.value);
        expectedRowElement.setAttribute('min', MagicConstant);
        
        let MagicSquare = window.createMagicSquare(n);
        
        if (RowSum > MagicConstant) {
            if (n % 2 === 1) {
                MagicSquare = window.incrementedMagicSquare(MagicSquare, RowSum);
            } else if (n % 4 === 0) {
                MagicSquare = window.incrementMatrix(MagicSquare, RowSum);
            }
        }
        
        if (r > 0) {
            MagicSquare = window.rotateMatrix(MagicSquare, (r / 90));
        }
        
        if (f == "true") {
            MagicSquare = window.mirrorFlip(MagicSquare);
        }
        
        if (job == "rotate" || job == "flip") {
            window.checkMagicSquare(MagicSquare, 0);
        } else if (job == "none") {
            // Do nothing
        } else {
            window.checkMagicSquare(MagicSquare, 100);
        }
        
        if (document.getElementById(wid + '-numberswitch') && 
            document.getElementById(wid + '-numberswitch').checked) {
            for (let r = 0; r < n; r++) {
                for (let c = 0; c < n; c++) {
                    MagicSquare[r][c] = '\u200E\u200F' + window.ArabToIndian(MagicSquare[r][c]) + '\u200E';
                }
            }
        }
        
        document.getElementById(wid + '-magicsquareoutput').value = window.formatMagicSquare(MagicSquare);
        document.getElementById(wid + '-boxedoutput').value = window.boxTheSquare(MagicSquare);
        document.getElementById(wid + '-htmloutput').value = window.createHTML(wid + '-pdfpngoutput', MagicSquare);
        
        window.highlightCode(
            document.getElementById(wid + '-htmloutput'), 
            document.getElementById(wid + '-highlightedoutput')
        );
        
        window.preEqualizeCells();
        const newAlgo = document.getElementById(wid + '-algorithm');
        if (newAlgo) {
            newAlgo.addEventListener('change', function() {
                if (lockCreator && lockCreator.checked && typeof window.generateMagicSquare === 'function') {
                    window.generateMagicSquare('check');
                }
            });
        }
    };

    // =========================================================================
    // KONTROL FONKSİYONU
    // =========================================================================
    window.checkMagicSquare = function(MagicSquare, delay) {
        const wid = window.magicSquareWidgetId;
        const n = MagicSquare.length;
        const expectedRowSum = document.getElementById(wid + '-rowsum').value;
        const MagicConstant = (n * (n * n + 1)) / 2;
        const checkresults = document.getElementById(wid + '-checkresult');
        
        if (!checkresults) return;
        
        let successpuan = 0;
        let hideandseek = 0;
        let checkresult = [];
        let shouttohtml = '';
        
        shouttohtml += `<span class="hideandseek${hideandseek} checkresults">Sihirli Sabit: </span>`;
        shouttohtml += `<span class="hideandseek${hideandseek+1} checkresults">${MagicConstant}</span>`;
        shouttohtml += `<span class="hideandseek${hideandseek+2} checkresults">, </span>`;
        checkresult.push(".hideandseek" + hideandseek, ".hideandseek" + (hideandseek+1), ".hideandseek" + (hideandseek+2));
        hideandseek += 3;
        
        for (let i = 0; i < n; i++) {
            const RowSum = MagicSquare[i].reduce((acc, val) => acc + val, 0);
            shouttohtml += `<span class="hideandseek${hideandseek} checkresults${RowSum == expectedRowSum ? '' : ' resultiswrong'}">Satır ${i+1}: </span>`;
            shouttohtml += `<span class="hideandseek${hideandseek+1} checkresults${RowSum == expectedRowSum ? '' : ' resultiswrong'}">${RowSum}</span>`;
            shouttohtml += `<span class="hideandseek${hideandseek+2} checkresults">, </span>`;
            checkresult.push(".hideandseek" + hideandseek, ".hideandseek" + (hideandseek+1), ".hideandseek" + (hideandseek+2));
            if (RowSum == expectedRowSum) successpuan++;
            hideandseek += 3;
        }
        
        for (let j = 0; j < n; j++) {
            let colSum = 0;
            for (let i = 0; i < n; i++) colSum += MagicSquare[i][j];
            shouttohtml += `<span class="hideandseek${hideandseek} checkresults${colSum == expectedRowSum ? '' : ' resultiswrong'}">Sütun ${j+1}: </span>`;
            shouttohtml += `<span class="hideandseek${hideandseek+1} checkresults${colSum == expectedRowSum ? '' : ' resultiswrong'}">${colSum}</span>`;
            shouttohtml += `<span class="hideandseek${hideandseek+2} checkresults">, </span>`;
            checkresult.push(".hideandseek" + hideandseek, ".hideandseek" + (hideandseek+1), ".hideandseek" + (hideandseek+2));
            if (colSum == expectedRowSum) successpuan++;
            hideandseek += 3;
        }
        
        let diag1Sum = 0, diag2Sum = 0;
        for (let i = 0; i < n; i++) {
            diag1Sum += MagicSquare[i][i];
            diag2Sum += MagicSquare[i][n - 1 - i];
        }
        
        shouttohtml += `<span class="hideandseek${hideandseek} checkresults${diag1Sum == expectedRowSum ? '' : ' resultiswrong'}">Ana Çapraz: </span>`;
        shouttohtml += `<span class="hideandseek${hideandseek+1} checkresults${diag1Sum == expectedRowSum ? '' : ' resultiswrong'}">${diag1Sum}</span>`;
        shouttohtml += `<span class="hideandseek${hideandseek+2} checkresults">, </span>`;
        checkresult.push(".hideandseek" + hideandseek, ".hideandseek" + (hideandseek+1), ".hideandseek" + (hideandseek+2));
        if (diag1Sum == expectedRowSum) successpuan++;
        hideandseek += 3;
        
        shouttohtml += `<span class="hideandseek${hideandseek} checkresults${diag2Sum == expectedRowSum ? '' : ' resultiswrong'}">Yan Çapraz: </span>`;
        shouttohtml += `<span class="hideandseek${hideandseek+1} checkresults${diag2Sum == expectedRowSum ? '' : ' resultiswrong'}">${diag2Sum}</span>`;
        checkresult.push(".hideandseek" + hideandseek, ".hideandseek" + (hideandseek+1));
        hideandseek += 3;
        
        checkresults.innerHTML = shouttohtml;
        window.HideAndSeek([], checkresult, delay);
        
        if (successpuan < (2 * n)) {
            $("#" + wid + "-copybtn").attr('disabled', 'disabled');
            $("#" + wid + "-savebtn").attr('disabled', 'disabled');
            $("#" + wid + "-rotate").attr('disabled', 'disabled');
            $("#" + wid + "-mirror").attr('disabled', 'disabled');
            $("#" + wid + "-numberswitch").attr('disabled', 'disabled');
        } else {
            $("#" + wid + "-copybtn").removeAttr('disabled');
            $("#" + wid + "-savebtn").removeAttr('disabled');
            $("#" + wid + "-rotate").removeAttr('disabled');
            $("#" + wid + "-mirror").removeAttr('disabled');
            $("#" + wid + "-numberswitch").removeAttr('disabled');
        }
    };

})(window, jQuery);
EOT;

// =========================================================================
// JAVASCRIPT ÇIKTISINI OLUŞTUR
// =========================================================================

echo "/*\n";
echo " * Magic Square Widget - Complete Build\n";
echo " * Version: " . esc_js( MAGIC_SQUARE_WIDGET_VERSION ) . "\n";
echo " * Build Date: " . esc_js( gmdate( 'Y-m-d H:i:s' ) ) . "\n";
echo " * Site: " . esc_js( get_site_url() ) . "\n";
echo " */\n\n";

if ( ! empty( $magic_square_style_options['custom_css'] ) ) {
    $magic_square_custom_css = str_replace( '`', '\\`', $magic_square_style_options['custom_css'] );
    $magic_square_custom_css = str_replace( '${', '\\${', $magic_square_custom_css );
    echo "window.magicSquareCustomCSS = " . wp_json_encode( $magic_square_custom_css ) . ";\n\n";
}

$magic_square_config = array(
    'id'            => 'metatronslove', // SABİT
    'color'         => $magic_square_options['color'],
    'position'      => $magic_square_options['position'],
    'margin_x'      => intval( $magic_square_options['margin_x'] ),
    'margin_y'      => intval( $magic_square_options['margin_y'] ),
    'message'       => $magic_square_options['message'],
    'description'   => $magic_square_options['description'],
    'button_content' => $magic_square_button_content,
    'pluginUrl'     => $magic_square_plugin_url
);
echo "window.config = " . wp_json_encode( $magic_square_config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . ";\n\n";

if ( ! empty( $magic_square_code_options['custom_js'] ) ) {
    echo "\n\n// =============================================\n";
    echo "// KULLANICI ÖZEL KODLARI\n";
    echo "// =============================================\n\n";
    // Özel kodu olduğu gibi ekle (zaten güvenli mi diye kontrol etmeye gerek yok,
	// çünkü kullanıcı bilinçli olarak ekliyor)
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo $magic_square_code_options['custom_js'];
    echo "\n";
}

echo "\n// =============================================\n";
echo "// Widget UI ve Core Magic Square Fonksiyonları\n";
echo "// =============================================\n\n";
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $magic_square_complete_js;
?>
