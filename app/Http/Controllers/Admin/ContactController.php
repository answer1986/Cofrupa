<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!session('admin_authenticated')) {
                return redirect()->route('admin.login');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.contacts.index');
    }



    /**
     * Importar contactos existentes desde el HTML
     */
    public function importExisting()
    {
        $existingContacts = [
            [
                'key' => 'contact_agustin_name',
                'value_es' => 'Agustín Marín Cobo',
                'value_en' => 'Agustín Marín Cobo',
                'section' => 'contacto',
                'type' => 'text',
                'description' => 'Nombre de Agustín Marín Cobo'
            ],
            [
                'key' => 'contact_agustin_email',
                'value_es' => 'agustin@onizzo.com',
                'value_en' => 'agustin@onizzo.com',
                'section' => 'contacto',
                'type' => 'text',
                'description' => 'Email de Agustín Marín Cobo'
            ],
            [
                'key' => 'contact_claudia_name',
                'value_es' => 'Claudia Marangunic',
                'value_en' => 'Claudia Marangunic',
                'section' => 'contacto',
                'type' => 'text',
                'description' => 'Nombre de Claudia Marangunic'
            ],
            [
                'key' => 'contact_claudia_email',
                'value_es' => 'cmarangunic@onizzo.com',
                'value_en' => 'cmarangunic@onizzo.com',
                'section' => 'contacto',
                'type' => 'text',
                'description' => 'Email de Claudia Marangunic'
            ],
            [
                'key' => 'contact_info_email',
                'value_es' => 'info@onizzo.com',
                'value_en' => 'info@onizzo.com',
                'section' => 'contacto',
                'type' => 'text',
                'description' => 'Email de información general'
            ],
            [
                'key' => 'contact_phone',
                'value_es' => '+56 2 2927 0470',
                'value_en' => '+56 2 2927 0470',
                'section' => 'contacto',
                'type' => 'text',
                'description' => 'Número de teléfono principal'
            ]
        ];

        foreach ($existingContacts as $contactData) {
            \App\Models\Content::firstOrCreate(
                ['key' => $contactData['key']],
                $contactData
            );
        }

        return redirect()->route('admin.contacts.index')
                        ->with('success', 'Contactos importados como contenidos editables exitosamente');
    }

    /**
     * Importar elementos del footer
     */
    public function importFooter()
    {
        $footerElements = [
            // Títulos de secciones
            [
                'key' => 'footer_company_title',
                'value_es' => 'Nuestra empresa',
                'value_en' => 'Our company',
                'section' => 'footer',
                'type' => 'text',
                'description' => 'Título de la sección empresa en el footer'
            ],
            [
                'key' => 'footer_contact_title',
                'value_es' => 'Contacto',
                'value_en' => 'Contact',
                'section' => 'footer',
                'type' => 'text',
                'description' => 'Título de la sección contacto en el footer'
            ],
            [
                'key' => 'footer_cert_title',
                'value_es' => 'Certificaciones',
                'value_en' => 'Certifications',
                'section' => 'footer',
                'type' => 'text',
                'description' => 'Título de la sección certificaciones en el footer'
            ],
            // Política de privacidad
            [
                'key' => 'footer_privacy_text',
                'value_es' => 'Políticas y privacidad',
                'value_en' => 'Privacy policy',
                'section' => 'footer',
                'type' => 'text',
                'description' => 'Texto del enlace de políticas y privacidad'
            ],
            [
                'key' => 'footer_privacy_url',
                'value_es' => '#',
                'value_en' => '#',
                'section' => 'footer',
                'type' => 'text',
                'description' => 'URL del enlace de políticas y privacidad'
            ],
            // Información de contacto
            [
                'key' => 'footer_phone_label',
                'value_es' => 'Teléfono',
                'value_en' => 'Phone',
                'section' => 'footer',
                'type' => 'text',
                'description' => 'Etiqueta del teléfono en el footer'
            ],
            [
                'key' => 'footer_phone',
                'value_es' => '562 2682 9200',
                'value_en' => '562 2682 9200',
                'section' => 'footer',
                'type' => 'text',
                'description' => 'Número de teléfono en el footer'
            ],
            [
                'key' => 'footer_email_label',
                'value_es' => 'Mail',
                'value_en' => 'Email',
                'section' => 'footer',
                'type' => 'text',
                'description' => 'Etiqueta del email en el footer'
            ],
            [
                'key' => 'footer_email',
                'value_es' => 'ventas@onizzo.com',
                'value_en' => 'ventas@onizzo.com',
                'section' => 'footer',
                'type' => 'text',
                'description' => 'Email en el footer'
            ],

            // Redes sociales (para cuando se descomenten)
            [
                'key' => 'footer_social_title',
                'value_es' => 'Síguenos',
                'value_en' => 'Follow us',
                'section' => 'footer',
                'type' => 'text',
                'description' => 'Título de la sección redes sociales'
            ],
            [
                'key' => 'footer_instagram_url',
                'value_es' => '#',
                'value_en' => '#',
                'section' => 'footer',
                'type' => 'text',
                'description' => 'URL de Instagram'
            ],
            [
                'key' => 'footer_facebook_url',
                'value_es' => '#',
                'value_en' => '#',
                'section' => 'footer',
                'type' => 'text',
                'description' => 'URL de Facebook'
            ]
        ];

        // Importar imágenes del footer
        $footerImages = [
            [
                'key' => 'footer_logo',
                'path' => './image/Onizzo-header.png',
                'alt_text_es' => 'Logo Onizzo',
                'alt_text_en' => 'Logo Onizzo',
                'section' => 'footer',
                'description' => 'Logo principal en el footer'
            ],
            [
                'key' => 'footer_cert_image',
                'path' => './image/icon/certificaciones.png',
                'alt_text_es' => 'Certificaciones',
                'alt_text_en' => 'Certifications',
                'section' => 'footer',
                'description' => 'Imagen de certificaciones en el footer'
            ],
            [
                'key' => 'footer_instagram_icon',
                'path' => './image/insta.png',
                'alt_text_es' => 'Instagram',
                'alt_text_en' => 'Instagram',
                'section' => 'footer',
                'description' => 'Icono de Instagram'
            ],
            [
                'key' => 'footer_facebook_icon',
                'path' => './image/facebook.png',
                'alt_text_es' => 'Facebook',
                'alt_text_en' => 'Facebook',
                'section' => 'footer',
                'description' => 'Icono de Facebook'
            ]
        ];

        // Importar contenidos de texto
        foreach ($footerElements as $elementData) {
            \App\Models\Content::firstOrCreate(
                ['key' => $elementData['key']],
                $elementData
            );
        }

        // Importar imágenes
        foreach ($footerImages as $imageData) {
            \App\Models\Image::firstOrCreate(
                ['key' => $imageData['key']],
                array_merge($imageData, ['is_active' => true])
            );
        }

        return redirect()->route('admin.contacts.index')
                        ->with('success', 'Footer importado como contenido editable exitosamente');
    }


}
