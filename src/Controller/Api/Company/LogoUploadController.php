<?php


namespace App\Controller\Api\Company;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class LogoUploadController
 *
 * @package App\Controller\Api\Company
 */
class LogoUploadController extends AbstractController
{
    /**
     * Handle uploaded logo file
     *
     * @param Request $request Http request
     *
     * @return Company
     */
    public function __invoke(Request $request): Company
    {
        $uploadedFile = $request->files->get('logoFile');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $company = new Company();
        $company->setName($request->request->get('name'));
        $company->setUrl($request->request->get('url'));
        $company->setLogoFile($uploadedFile);

        return $company;
    }
}