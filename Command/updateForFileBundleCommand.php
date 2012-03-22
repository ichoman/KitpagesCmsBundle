<?php
namespace Kitpages\CmsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kitpages\FileBundle\Entity\FileInterface;

class updateForFileBundleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kitCms:updateForFileBundle')
            ->setHelp(<<<EOT
The <info>kitCms:updateForFileBundle</info> command updates for the fileBundle Version1.2.0.
EOT
            )
            ->setDescription('update for kitFileBundle v1.2.0')
            ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine')->getEntityManager('default');

        $fileManager = $this->getContainer()->get('kitpages.file.manager');

        $query = $em->getConnection()->executeUpdate('TRUNCATE TABLE `cms_block_publish`');

        $query = $em->getConnection()->executeUpdate('TRUNCATE TABLE `cms_nav_publish`');

        $query = $em->getConnection()->executeUpdate('TRUNCATE TABLE `cms_zone_publish`');

        $query = $em->getConnection()->executeUpdate('TRUNCATE TABLE `cms_page_publish`');

        $blockList = $em->getRepository('Kitpages\CmsBundle\Entity\Block')->findAll();
        foreach($blockList as $block) {
            $blockData = $block->getData();
            if (isset($blockData['root']) && count($blockData['root'])>0 ) {
                foreach($blockData['root'] as $field => $idMedia) {
                    if (substr($field, '0', '6') == 'media_') {
                        if ($idMedia != null) {
                            $file = $em->getRepository('KitpagesFileBundle:File')->findById($idMedia);
//                            echo var_dump($idMedia);exit();
//                            echo var_dump();
                            if ($file instanceof FileInterface) {
                                $file->setStatus(FileInterface::STATUS_VALID);
                                $file->setItemClass('KitpagesCmsBundle:Block');
                                $file->setItemId($block->getId());
                                $em->persist($file);
                                $em->flush();
                            }
                        }
                    }
                }
            }
        }

        $fileTempList = $em->getRepository('Kitpages\FileBundle\Entity\File')->findByStatus("temp");
        foreach ($fileTempList as $fileTemp) {
            $fileManager->delete($fileTemp);
        }

        $query = $em->createQuery('SELECT f FROM Kitpages\FileBundle\Entity\File f WHERE f.itemId IS NULL');
        $fileTempList = $query->getResult();
        foreach ($fileTempList as $fileTemp) {
            $fileManager->delete($fileTemp);
        }
        $dir = $fileManager->getFilePublicAbsoluteRootDir();
        if (is_dir($dir)) {
            $fileManager->getUtil()->rmdirr($fileManager->getFilePublicAbsoluteRootDir());
        }

        $output->writeln('Your version is compatible with the version1.2 of FileBundle');

    }
}