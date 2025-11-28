@props(['url'])
<tr>
    <td class="header" style="padding: 0; margin: 0; text-align: center;">

        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" style="padding: 0;">
                    <a href="{{ $url }}" style="display: block; width: 100%;">
                        {{--
                           PENTING:
                           1. src: Ganti dengan link gambar BARU yang ukurannya 1140x350px
                           2. height: auto (JANGAN DIUBAH jadi pixel, biar tidak gepeng)
                        --}}
                        <img src="https://i.imgur.com/tgyaLRb.png"
                             class="logo"
                             alt="Foodlink Logo"
                             style="width: 100%; max-width: 570px; height: auto; border: none; display: block; border-radius: 5px;">
                    </a>
                </td>
            </tr>
        </table>

    </td>
</tr>
