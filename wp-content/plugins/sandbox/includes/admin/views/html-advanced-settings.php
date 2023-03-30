<?php
/**
 * Admin View: Advance Settings
 *
 * @package Sandbox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<script type="text/javascript">
    //Event Listener for Copy buttons.
    document.addEventListener('click', (e) => {
        if (!e.target.matches('.cpy-bnt')) {
            return;
        }

        const buttonId = e.target.id;
        const sourceId = buttonId.replace('cpy', 'cred');
        const source = document.querySelector('#'+sourceId);
        const button = e.target;

        if (navigator.clipboard) {
            console.log('Clipboard API available!');
            let copyValue = source.textContent;
            copy2clipboard(copyValue);
            updateBtn(button);
        } else {
            console.log('Clipboard API not available :( - Trying fallback deprecated method');
            let selection = document.getSelection();
            let range = document.createRange();
            range.selectNode(source);
            selection.removeAllRanges();
            selection.addRange(range);
            console.log('copy success', document.execCommand('copy'));
            selection.removeAllRanges();
            updateBtn(button);
        }
        event.preventDefault();
    });

    async function copy2clipboard(value) {
        await navigator.clipboard.writeText(value);
    }

    function updateBtn(button) {
        let ogColor = button.style.backgroundColor;
        let ogText = button.textContent;
        button.style.backgroundColor = '#e6ffe6';
        button.textContent = 'Copied!';
        button.strong = 'yes';
        setTimeout(function(){
            button.style.backgroundColor = ogColor;
            button.textContent = ogText;
        }, 3000);
    }
</script>

<div class="sandbox-settings-wrapper">

    <h2><?php _e('WordPress'); ?></h2>

    <table>
        <tr>
            <td class="table-label"><?php _e('One Click Login:'); ?></td>
            <td id="cred1" class="table-value"><?php echo $this->getOneClickUrl(); ?></td>
            <td class="table-copy-btn"><button class="cpy-bnt" id="cpy1"> Copy </button></td>
        </tr>
        <tr>
            <td class="table-label"><?php _e('Username:');?></td>
            <td id="cred2" class="table-value"><?php echo $this->data['options']['username'];?></td>
            <td class="table-copy-btn"><button class="cpy-bnt" id="cpy2"> Copy </button></td>
        </tr>
        <tr>
            <td class="table-label"><?php _e('Password:');?></td>
            <td id="cred3" class="table-value"><?php echo $this->data['options']['password'];?></td>
            <td class="table-copy-btn"><button class="cpy-bnt" id="cpy3"> Copy </button></td>
        </tr>
    </table>

</div>

<div class="sandbox-settings-wrapper">

    <h2><?php _e('SSH Credentials'); ?></h2>

    <table>
        <tr>
            <td class="table-label"><?php _e('Hostname:'); ?></td>
            <td id="cred4" class="table-value"><?php echo str_replace('.pro', '.io', $this->getSetting('server_web_host'));?></td>
            <td class="table-copy-btn"><button class="cpy-bnt" id="cpy4"> Copy </button></td>
        </tr>
        <tr>
            <td class="table-label"><?php _e('Username:');?></td>
            <td id="cred5" class="table-value"><?php echo $this->getSetting('server_name');?></td>
            <td class="table-copy-btn"><button class="cpy-bnt" id="cpy5"> Copy </button></td>
        </tr>
        <tr>
            <td class="table-label"><?php _e('Password:');?></td>
            <td id="cred6" class="table-value"><?php echo $this->getSetting('unix_pass');?></td>
            <td class="table-copy-btn"><button class="cpy-bnt" id="cpy6"> Copy </button></td>
        </tr>
        <tr>
            <td class="table-label"><?php _e('Login:');?></td>
            <td id="cred7" class="table-value"><?php echo 'ssh -p ' . $this->getSetting('port') . ' ' . $this->getSetting('server_name') . '@' . str_replace('.pro', '.io', $this->getSetting('server_web_host'));?></td>
            <td class="table-copy-btn"><button class="cpy-bnt" id="cpy7"> Copy </button></td>
        </tr>
    </table>

</div>

<div class="sandbox-settings-wrapper">

    <h2><?php _e('SFTP Credentials'); ?></h2>

    <table>
        <tr>
            <td class="table-label"><?php _e('Server:'); ?></td>
            <td id="cred8" class="table-value"><?php echo str_replace('.pro', '.io', $this->getSetting('server_web_host'));?></td>
            <td class="table-copy-btn"><button class="cpy-bnt" id="cpy8"> Copy </button></td>
        </tr>
        <tr>
            <td class="table-label"><?php _e('Username:');?></td>
            <td id="cred9" class="table-value"><?php echo $this->getSetting('server_name');?></td>
            <td class="table-copy-btn"><button class="cpy-bnt" id="cpy9"> Copy </button></td>
        </tr>
        <tr>
            <td class="table-label"><?php _e('Password:');?></td>
            <td id="cred10" class="table-value"><?php echo $this->getSetting('unix_pass');?></td>
            <td class="table-copy-btn"><button class="cpy-bnt" id="cpy10"> Copy </button></td>
        </tr>
        <tr>
            <td class="table-label"><?php _e('Port:');?></td>
            <td id="cred11" class="table-value"><?php echo $this->getSetting('port');?></td>
            <td class="table-copy-btn"><button class="cpy-bnt" id="cpy11"> Copy </button></td>
        </tr>
    </table>

</div>

<div class="sandbox-settings-wrapper">

    <h2><?php _e('PHP Version'); ?></h2>

    <table>
        <tr>
            <td class="table-label"><?php _e('Current version:'); ?></td>
            <td><span class="sandbox-php-version"><strong><?php echo $this->getSetting('php_version');?></strong></span></td>
        </tr>
        <tr>
            <td class="table-label"><?php _e('Change To:');?></td>
            <td>
                <select name="php_version">
                    <?php foreach (Sandbox_API::$php_version as $version):?>
                    <?php if ($version === end(Sandbox_API::$php_version)) { ?>
					<option selected="selected" value="<?php echo $version;?>"><?php echo $version;?></option>
					<?php } else { ?>
					<option value="<?php echo $version;?>"><?php echo $version;?></option>
                    <?php } endforeach;?>
                </select>
            </td>
        </tr>
    </table>

    <input type="hidden" name="sandbox_advanced_settings" value="1"/>

    <p class="submit">
        <input name="save" class="button-primary sandbox-save-button save-advanced-settings" type="submit" value="<?php esc_attr_e( 'Save Changes', 'sandbox' ); ?>" />
        <?php wp_nonce_field( 'sandbox-settings' ); ?>
    </p>

</div>
