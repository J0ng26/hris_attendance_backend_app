<?php

namespace App\Modules\Attendance\Service;

use App\Models\User;
use App\Modules\Attendance\Repository\AttendanceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AttendanceService
{
    private AttendanceRepositoryInterface $attendanceRepository;

    public function __construct(AttendanceRepositoryInterface $attendanceRepository)
    {
        $this->attendanceRepository = $attendanceRepository;
    }

    public function record(User $user, array $data, Request $request)
    {
        $photoPath = '';
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('attendance_photos', 'public');
            $photoPath = url('storage/' . $path);
        } elseif (!empty($data['photo_path'])) {
            $photoPath = $data['photo_path'];
        }

        $recordTs = !empty($data['timestamp'])
            ? \Carbon\Carbon::parse($data['timestamp'])->setTimezone('Asia/Manila')
            : \Carbon\Carbon::now('Asia/Manila');

        $record = $this->attendanceRepository->create([
            'user_id' => $user->id,
            'type' => $data['type'],
            'timestamp' => $recordTs,
            'photo_path' => $photoPath,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ]);

        $userName = trim($user->first_name . ' ' . $user->last_name) ?: $user->username;

        return Response::json([
            'id' => $record->id,
            'userId' => $record->user_id,
            'userName' => $userName,
            'type' => $record->type,
            'timestamp' => \Carbon\Carbon::parse($record->timestamp)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s'),
            'photoPath' => $record->photo_path,
            'latitude' => $record->latitude ? (float) $record->latitude : null,
            'longitude' => $record->longitude ? (float) $record->longitude : null,
        ], 201);
    }

    public function myRecords(User $user)
    {
        $records = $this->attendanceRepository->getByUserId($user->id);
        $userName = trim($user->first_name . ' ' . $user->last_name) ?: $user->username;

        return Response::json(
            $records->map(fn ($r) => [
                'id' => $r->id,
                'userId' => $r->user_id,
                'userName' => $userName,
                'type' => $r->type,
                'timestamp' => \Carbon\Carbon::parse($r->timestamp)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s'),
                'photoPath' => $r->photo_path,
                'latitude' => $r->latitude ? (float) $r->latitude : null,
                'longitude' => $r->longitude ? (float) $r->longitude : null,
            ])
        );
    }

    public function today(User $user)
    {
        $records = $this->attendanceRepository->getTodayByUserId($user->id);
        $userName = trim($user->first_name . ' ' . $user->last_name) ?: $user->username;

        return Response::json(
            $records->map(fn ($r) => [
                'id' => $r->id,
                'userId' => $r->user_id,
                'userName' => $userName,
                'type' => $r->type,
                'timestamp' => \Carbon\Carbon::parse($r->timestamp)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s'),
                'photoPath' => $r->photo_path,
                'latitude' => $r->latitude ? (float) $r->latitude : null,
                'longitude' => $r->longitude ? (float) $r->longitude : null,
            ])
        );
    }

    public function status(User $user)
    {
        $latest = $this->attendanceRepository->getLatestByUserId($user->id);
        $isTimedIn = $latest ? ($latest->type === 'logIn') : false;

        return Response::json([
            'is_timed_in' => $isTimedIn,
            'last_record' => $latest ? [
                'id' => $latest->id,
                'type' => $latest->type,
                'timestamp' => \Carbon\Carbon::parse($latest->timestamp)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s'),
            ] : null,
        ]);
    }
}
