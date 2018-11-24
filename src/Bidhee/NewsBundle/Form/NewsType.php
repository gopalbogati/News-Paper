<?php

namespace Bidhee\NewsBundle\Form;

use CG\Tests\Proxy\Fixture\Entity;
use Doctrine\ORM\EntityRepository;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Bidhee\CategoryBundle\Entity\Category;
use Bidhee\NewsBundle\Entity\News;
use Bidhee\UserBundle\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewsType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','textarea', [
                'label' => 'Title',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 2
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('subtitle','textarea', [
                'label' => 'Subtitle',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 2
                ],
            ])
            ->add('newsSlug', 'text', [
                'label' => 'News Slug',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('introText', 'textarea', [
                'label' => 'Intro Text (teaser content/excerpt)',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4
                ]
            ])
            ->add('content', 'ckeditor', [
                'config_name' => 'my_config',
                'label' => 'Content',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 100
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('categories', 'entity', [
                'label' => 'Category',
                'class' => 'Bidhee\CategoryBundle\Entity\Category',
                'property' => 'label',
                'multiple' => true,
                'choices' => $options['em']->getRepository(Category::class)->getCategorySelectList(),
                'attr' => [
                    'class' => 'form-control, select2'
                ]
            ])
            ->add('author', 'entity', [
                'label' => 'Author',
                'required' => false,
                'class' => 'Bidhee\UserBundle\Entity\Author',
                'property' => 'name',
                'empty_value' => ' -- Select Author --',
                'placeholder' => $options['em']->getRepository(Author::class)->find(880),
                'attr' => [
                    'class' => 'form-control select2'
                ]
            ])
            ->add('authorcheck', 'checkbox', [
                'label' => 'Author Menu',
                'required' => false,
                'attr' => [
                    'class' => 'my-switch'
                ],

            ])
            ->add('status', 'choice', [
                'label' => 'status',
                'required' => true,
                'choices' => News::$newsStatus,
                'data' => News::NEWS_STATUS_PUBLISHED,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('isBreakingNews', 'checkbox', [
                'label' => 'Is Breaking News',
                'required' => false,
                'attr' => [
                    'class' => 'my-switch'
                ]
            ])
            ->add('publishOn', TextType::class, [
                'label' => 'Publish News On',
                'attr' => [
                    'class' => 'form-control input-inline datetimepicker',
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('isImportantNews', 'checkbox', [
                'label' => 'मुख्य समाचार',
                'required' => false,
                'attr' => [
                    'class' => 'my-switch'
                ]
            ])
            ->add('isFeaturedNews', 'checkbox', [
                'label' => 'Is Featured News',
                'required' => false,
                'attr' => [
                    'class' => 'my-switch'
                ]
            ])
            ->add('featuredImage', 'elfinder', [
                'label' => 'Featured Image',
                'required' => false,
                'instance' => 'form',
                'enable' => true,
                'attr' => [
                    'class' => 'form-control featuredImage',
                    'readonly' => 'readonly',
                    'placeholder' => 'Click here to browse image.'
                ]
            ])
            ->add('videoLink', 'text', [
                'required' => false,
                'label' => 'Youtube Video Id',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('caption', 'text', [
                'required' => false,
                'label' => 'Image/Video Caption',
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Bidhee\NewsBundle\Entity\News',
            'em' => 'Bidhee\CategoryBundle\Repository\CategoryRepository'
        ]);
    }
}
