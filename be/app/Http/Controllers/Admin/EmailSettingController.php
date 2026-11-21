<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestEmailRequest;
use App\Http\Requests\Admin\UpdateEmailSettingRequest;
use App\Models\EmailSetting;
use App\Models\WebsiteIdentity;
use App\Services\EmailSettingService;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailSettingController extends Controller
{
    public function index()
    {
        $setting = EmailSetting::singleton();
        $identity = WebsiteIdentity::singleton();

        return view('pages.email-setting.index', [
            'title' => 'Pengaturan Email',
            'setting' => $setting,
            'identity' => $identity,
        ]);
    }

    public function update(UpdateEmailSettingRequest $request)
    {
        DB::beginTransaction();

        try {
            $setting = EmailSetting::singleton();
            $data = $request->only([
                'mail_driver',
                'mail_host',
                'mail_port',
                'mail_encryption',
                'mail_username',
                'mail_password',
                'mail_timeout',
                'from_address',
                'from_name',
            ]);

            if (blank($request->input('mail_password'))) {
                unset($data['mail_password']);
            }

            $data['mail_encryption'] = $request->input('mail_encryption') ?: null;
            $data['is_active'] = $request->boolean('is_active');

            $setting->update($data);
            DB::commit();

            EmailSettingService::apply();

            return redirect()->route('email-setting.index')->with('success', 'Pengaturan email berhasil disimpan.');
        } catch (Throwable $th) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', $this->safeErrorMessage($th));
        }
    }

    public function test(TestEmailRequest $request)
    {
        $recipient = $request->validated('recipient');

        EmailSettingService::apply();

        try {
            Mail::raw("Halo,\n\nIni adalah email uji dari Sagita Wedding. Jika Anda menerima pesan ini, pengaturan email sudah berfungsi.\n\nTerima kasih.", function (Message $message) use ($recipient) {
                $message->to($recipient)
                    ->subject('Uji Pengaturan Email Sagita Wedding');
            });

            return redirect()->back()->with('success', 'Email uji berhasil dikirim ke '.$recipient.'.');
        } catch (Throwable $th) {
            report($th);

            return redirect()->back()->withInput()->with('error', $this->safeErrorMessage($th));
        }
    }
}
