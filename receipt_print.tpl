<style type="text/css" media="all">
    * {
        font-family: "Times New Roman", Times, serif;
        font-size: 18px;
        color: #000000;
    }
    body {
        margin:0px;
        padding: 0px;
        font-family: "Times New Roman", Times, serif;
        font-size: 24px;
        color: #000000;
    }
    hr {
        page-break-after: always;
        display:none;
        border:0px none;
    }
    .rot-neg-90 {
        /* rotate -90 deg, not sure if a negative number is supported so I used 270 */
        -moz-transform: rotate(270deg);
        -moz-transform-origin: 50% 50%;
        -webkit-transform: rotate(270deg);
        -webkit-transform-origin: 50% 50%;/* IE support too convoluted for the time I've got on my hands... */
    }
    table, td {
        border:0px dashed;
        border-collapse:collapse;
    }
    .image {
        text-align: center;
    }

</style>
<div class="content" style="width: 762px; border: 0px solid;  padding:0px;">
    <?php foreach($print_receipts as $receipt): ?>
    <div style="/*height:530px;*/ overflow:hidden; padding: 0 15px;">
        <div class="image">
            <img src="<?php echo HTTP_IMAGE; ?>/receipt_logo.jpg" width="250"/>
        </div>
        <?php if($receipt['mode_of_payment']=='Cash'): ?>
        <table cellspacing="0" cellpadding="0" style="line-height: 35px; width: 100%;">
            <tr>
                <td width="40%">Date: <?php echo date('d-M-Y',strtotime($receipt['date'])); ?></td>
                <td width="20%"></td>
                <td width="40%" style="text-align: right;">Receipt No: <?php echo $receipt['receipt_identity']; ?></td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">Received a sum of Rs. <b><?php echo number_format(round($receipt['amount'])); ?>/-</b> (Rupees: <?php echo Number2Words(round($receipt['amount'])); ?>) only.</td>
            </tr>
            <tr>
                <td colspan="3">From <?php echo $receipt['momin']; ?>.</td>
            </tr>
            <tr>
                <td colspan="3">By Cash as Sila Fitra Vajebaat during Shere Ramadan <?php echo $receipt['hyear']; ?>h.</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="text-align: right;">Aamil Saheb / Mamoor</td>
            </tr>
        </table>
        <br />
        <br />
        <br />
        <table border="1">
            <tr>
                <td colspan="3" style="text-align: left; font-family: Courier; font-size: 8pt; font-style: oblique; border-top: 1px solid black;">Receipt not Valid without signature and Dawat-e-hadiyah Stamp</td>
            </tr>
            <tr>
                <td style="text-align: left; font-family: Courier; font-size: 7pt;"><?php echo $session['user_id']; ?></td>
                <td style="text-align: center; font-family: Courier; font-size: 7pt;"><?php echo $session['mohallah_id']; ?></td>
                <td style="text-align: right; font-family: Courier; font-size: 7pt;"><?php echo $session['session_id']; ?></td>
            </tr>
        </table>
        <?php elseif($receipt['mode_of_payment']=='Bank'): ?>
        <table cellspacing="0" cellpadding="0" style="line-height: 35px; width: 100%;">
            <tr>
                <td width="40%">Date: <?php echo date('d-M-Y',strtotime($receipt['date'])); ?></td>
                <td width="20%"></td>
                <td width="40%" style="text-align: right;">Receipt No: <?php echo $receipt['receipt_identity']; ?></td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">Received a sum of Rs. <b><?php echo number_format(round($receipt['amount'])); ?>/-</b> (Rupees: <?php echo Number2Words(round($receipt['amount'])); ?>) only.</td>
            </tr>
            <tr>
                <td colspan="3">From <?php echo $receipt['momin']; ?></td>
            </tr>
            <tr>
                <td colspan="3">By Cheque/P.O.No.: <b><?php echo $receipt['instrument_no']; ?></b> dated <b><?php echo date('d-M-Y',strtotime($receipt['instrument_date'])); ?></b>, drawn on <?php echo $receipt['bank_name']; ?>, as Sila Fitra Vajebaat during Shere Ramadan <?php echo $receipt['hyear']; ?>h.</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="text-align: right;">Aamil Saheb / Mamoor</td>
            </tr>
        </table>
        <br />
        <br />
        <br />
        <table border="1">
            <tr>
                <td colspan="3" style="text-align: left; font-family: Courier; font-size: 8pt; font-style: oblique; border-top: 1px solid black;">Receipt not Valid without signature and Dawat-e-hadiyah Stamp</td>
            </tr>
            <tr>
                <td style="text-align: left; font-family: Courier; font-size: 7pt;"><?php echo $session['user_id']; ?></td>
                <td style="text-align: center; font-family: Courier; font-size: 7pt;"><?php echo $session['mohallah_id']; ?></td>
                <td style="text-align: right; font-family: Courier; font-size: 7pt;"><?php echo $session['session_id']; ?></td>
            </tr>
        </table>
        <?php endif; ?>
    </div>
    <hr style="height:0px; display:block;" />
    <?php endforeach; ?>
</div>
<?php if(!isset($this->request->get['save_only'])): ?>
<script type="text/javascript"><!--
    window.print();
    window.close();
    //   setTimeout(window.close(),1000);
//--></script>

<?php endif; ?>