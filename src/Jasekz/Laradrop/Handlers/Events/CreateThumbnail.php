<?php
namespace Jasekz\Laradrop\Handlers\Events;

use Jasekz\Laradrop\Events\FileWasUploaded;
use Jasekz\Laradrop\Services\File as FileService;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Exception;

class CreateThumbnail {

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Handle the event.
     *
     * @param FileWasUploaded $event            
     * @return void
     */
    public function handle(FileWasUploaded $event)
    {
        try {
            if (file_exists($this->fileService->getInitialUploadsPath() . '/' . $event->data['fileName'])) {

                $img = Image::make($this->fileService->getInitialUploadsPath() . '/' . $event->data['fileName']);
                $img->resize(150, 150);
                $img->save($this->fileService->getInitialUploadsPath() . '/_thumb_' . $event->data['fileName']);
                $img->destroy();
            }
        } 

        catch (Exception $e) {
            throw $e;
        }
    }
}
