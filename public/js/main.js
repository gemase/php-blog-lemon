//Constantes globales.
const tokenCsrf = document.querySelector('meta[name="csrf-token"]')

const UI = (() => {
    'use strict'

    /**
     * Bloquea botón submit de formulario.
     * @param {*} boton Elemento botón a bloquear.
     * @param {*} bloquea true: Bloquea botón,
     * false: Desbloquea botón.
     */
    const bloqueaBotonForm = (boton, bloquea = true) => {
        if (bloquea) {
            boton.disabled = true;
            boton.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...`
        } else {
            boton.disabled = false
            boton.innerHTML = 'Confirmar'
        }
    }

    /**
     * Efecto desvanecer a un elmento.
     * FIXME: Datelle con múltiples peticiones en botones.
     * @param {*} contenedor Elemento contenedor a desvanecer.
     * @param {*} show true: Muestra elemento, false: oculta elemento.
     * @param {*} retardo Tiempo de retardo para ocultar el elemento.
     */
    const fadeElemento = (contenedor, show = true, retardo = 3000) => {
        if (show) {
            contenedor.classList.remove('fade-elemento-hide')
            contenedor.classList.add('fade-elemento-show')
        } else {
            setTimeout(() => {
                contenedor.classList.remove('fade-elemento-show')
                contenedor.classList.add('fade-elemento-hide')
            }, retardo)
        }
    }

    /**
     * Muestra mensaje de alerta.
     * @param {*} contenedor Elemento contenedor de la alerta.
     * @param {*} texto Texto ó mensaje de la alerta.
     * @param {*} tipo Tipo de alerta, ej: alert-success, 
     * alert-danger, alert-warning, alert-info.
     */
    const muestraAlerta = (contenedor, texto, tipo) => {
        let html = `
        <div class="alert ${tipo} pt-1 pb-1 pr-2 pl-2 text-center alert-dismissible fade show" role="alert">
            ${texto}
            <button type="button" class="border-0 bg-transparent" data-bs-dismiss="alert" aria-label="Close">x</button>
        </div>`
        contenedor.innerHTML = html
    }

    /**
     * Muestra toast.
     * @param {*} contenedor Elemento contenedor del toast.
     * @param {*} texto Texto ó mensaje del toast.
     * @param {*} tipo Tipo de toast color de fondo, ej: bg-success,
     * bg-danger, bg-warning, bg-info.
     */
    const muestraToast = (contenedor, texto, tipo) => {
        let html = `
        <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 11">
            <div id="liveToast" class="toast align-items-center text-white ${tipo} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${texto}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>`
        contenedor.innerHTML = html
        const liveToast = document.getElementById('liveToast')
        let toast = new bootstrap.Toast(liveToast)
        toast.show()
    }

    return {
        bloqueaBotonForm: bloqueaBotonForm,
        muestraAlerta: muestraAlerta,
        muestraToast: muestraToast
    }
})()

const HttpCtrl = ((UI) => {
    'use strict'
    
    /**
     * Petición ó solicitud al servidor.
     * @param {*} url Enlace al controlador.
     * @param {*} data Datos del formulario.
     * @returns Json
     */
    const post = async (url, data) => {
        const header = new Headers({'csrf-token': tokenCsrf.content})
        const resp = await fetch(url, {
            method: 'POST',
            body: data,
            headers: header
        })
        return await resp.json()
    }

    /**
     * Petición ó solicitud para uso general.
     * @param {*} event Evento del submit.
     * @param {*} ctrl Nombre ó enlace del controlador.
     * @param {*} boton Elemento del botón submit.
     * @param {*} contenedorAlerta Elemento del contenedor de la alerta.
     * @param {*} contenedorToast Elemento del contenedor del toast.
     */
    const postGeneral = (event, ctrl, boton, contenedorAlerta, contenedorToast) => {
        const form = new FormData(event.currentTarget)
        UI.bloqueaBotonForm(boton)
        post(ctrl, form).then(respuesta => {
            UI.bloqueaBotonForm(boton, false)
            //Alerta a mostrar.
            if (respuesta.tipoAlerta && respuesta.textoAlerta) {
                UI.muestraAlerta(contenedorAlerta, respuesta.textoAlerta, respuesta.tipoAlerta)
            }

            //Toast a mostrar.
            if (respuesta.tipoToast && respuesta.textoToast) {
                UI.muestraToast(contenedorToast, respuesta.textoToast, respuesta.tipoToast)
            }

            //Token ha actualizar.
            if (respuesta.nuevoToken) {
                tokenCsrf.content = respuesta.nuevoToken
            }

            //Limpia campos del formulario.
            if (respuesta.limpiaForm) {
                event.target.reset()
            }

            //Recarga o redirecciona página
            if (respuesta.url) {
                if (typeof respuesta.url === 'string') {
                    window.location.replace(respuesta.url)
                } else {
                    window.location.reload(true)
                }
            }
        }).catch((e) => {
            UI.bloqueaBotonForm(boton, false)
            let msgT = `Se detectó una inconsistencia en el sistema, 
            por favor vuelva a intentar. Si la inconsistencia persiste 
            favor de informar a soporte. Mensaje: ${e.message}`
            let tipoAlertaT = 'alert-danger'
            UI.muestraAlerta(contenedorAlerta, msgT, tipoAlertaT)
        })
    }

    return {
        postGeneral: postGeneral
    }
})(UI)

const Usuario = ((HttpCtrl) => {
    'use strict'

    const eventosNuevoUsuario = () => {
        const formNuevoUsuario = document.getElementById('formNuevoUsuario'),
        botonNuevoUsuario = document.getElementById('botonNuevoUsuario'),
        contAlertaNuevoUsuario = document.getElementById('contAlertaNuevoUsuario')
        
        formNuevoUsuario.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, 'postNuevo', botonNuevoUsuario, contAlertaNuevoUsuario)
        })
    }

    const eventosLogin = () => {
        const formLogin = document.getElementById('formLogin'),
        botonLogin = document.getElementById('botonLogin'),
        contAlertaLogin = document.getElementById('contAlertaLogin')
        
        formLogin.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, 'postLogin', botonLogin, contAlertaLogin)
        })
    }

    const eventosEditaUsuInfGeneral = () => {
        const formEditaUsuInfGeneral = document.getElementById('formEditaUsuInfGeneral'),
        botonEditaUsuInfGeneral = document.getElementById('botonEditaUsuInfGeneral'),
        contAlertaEditaUsuInfGeneral = document.getElementById('contAlertaEditaUsuInfGeneral')
        
        formEditaUsuInfGeneral.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, 'postEditaInfGeneral', botonEditaUsuInfGeneral, contAlertaEditaUsuInfGeneral)
        })
    }

    const eventosEditaUsuInfCuenta = () => {
        const formEditaUsuInfCuenta = document.getElementById('formEditaUsuInfCuenta'),
        botonEditaUsuInfCuenta = document.getElementById('botonEditaUsuInfCuenta'),
        contAlertaEditaUsuInfCuenta = document.getElementById('contAlertaEditaUsuInfCuenta')
        
        formEditaUsuInfCuenta.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, 'postEditaInfCuenta', botonEditaUsuInfCuenta, contAlertaEditaUsuInfCuenta)
        })
    }

    const eventosEditaUsuInfClave = () => {
        const formEditaUsuInfClave = document.getElementById('formEditaUsuInfClave'),
        botonEditaUsuInfClave = document.getElementById('botonEditaUsuInfClave'),
        contAlertaEditaUsuInfClave = document.getElementById('contAlertaEditaUsuInfClave')
        
        formEditaUsuInfClave.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, 'postEditaInfClave', botonEditaUsuInfClave, contAlertaEditaUsuInfClave)
        })
    }

    const eventosCrearUsuario = () => {
        const formCreaUsuario = document.getElementById('formCreaUsuario'),
        botonCreaUsuario = document.getElementById('botonCreaUsuario'),
        contAlertaCreaUsuario = document.getElementById('contAlertaCreaUsuario')
        
        formCreaUsuario.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, 'postCrear', botonCreaUsuario, contAlertaCreaUsuario)
        })
    }

    const eventosActualizaUsuInfGeneral = () => {
        const formActualizaUsuInfGeneral = document.getElementById('formActualizaUsuInfGeneral'),
        botonActualizaUsuInfGeneral = document.getElementById('botonActualizaUsuInfGeneral'),
        contAlertaActualizaUsuInfGeneral = document.getElementById('contAlertaActualizaUsuInfGeneral')
        
        formActualizaUsuInfGeneral.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, '/usuarios/postEditaInfGeneralAccesoCatalogo', botonActualizaUsuInfGeneral, contAlertaActualizaUsuInfGeneral)
        })
    }

    const eventosActualizaUsuInfCuenta = () => {
        const formActualizaUsuInfCuenta = document.getElementById('formActualizaUsuInfCuenta'),
        botonActualizaUsuInfCuenta = document.getElementById('botonActualizaUsuInfCuenta'),
        contAlertaActualizaUsuInfCuenta = document.getElementById('contAlertaActualizaUsuInfCuenta')
        
        formActualizaUsuInfCuenta.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, '/usuarios/postEditaInfCuentaAccesoCatalogo', botonActualizaUsuInfCuenta, contAlertaActualizaUsuInfCuenta)
        })
    }

    const eventosActualizaUsuInfClave = () => {
        const formActualizaUsuInfClave = document.getElementById('formActualizaUsuInfClave'),
        botonActualizaUsuInfClave = document.getElementById('botonActualizaUsuInfClave'),
        contAlertaActualizaUsuInfClave = document.getElementById('contAlertaActualizaUsuInfClave')
        
        formActualizaUsuInfClave.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, '/usuarios/postEditaInfClaveAccesoCatalogo', botonActualizaUsuInfClave, contAlertaActualizaUsuInfClave)
        })
    }

    return {
        eventosNuevoUsuario: eventosNuevoUsuario,
        eventosLogin: eventosLogin,
        eventosEditaUsuInfGeneral: eventosEditaUsuInfGeneral,
        eventosEditaUsuInfCuenta: eventosEditaUsuInfCuenta,
        eventosEditaUsuInfClave: eventosEditaUsuInfClave,
        eventosCrearUsuario: eventosCrearUsuario,
        eventosActualizaUsuInfCuenta: eventosActualizaUsuInfCuenta,
        eventosActualizaUsuInfClave: eventosActualizaUsuInfClave,
        eventosActualizaUsuInfGeneral: eventosActualizaUsuInfGeneral
    }
})(HttpCtrl)

const Perfil = ((HttpCtrl) => {
    'use strict'

    const eventosCrearPerfil = () => {
        const formCrearPerfil = document.getElementById('formCrearPerfil'),
        botonCrearPerfil = document.getElementById('botonCrearPerfil'),
        contAlertaCrearPerfil = document.getElementById('contAlertaCrearPerfil')
        
        formCrearPerfil.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, 'postCrear', botonCrearPerfil, contAlertaCrearPerfil)
        })
    }

    const eventosEditarPerfil = () => {
        const formEditarPerfil = document.getElementById('formEditarPerfil'),
        botonEditarPerfil = document.getElementById('botonEditarPerfil'),
        contAlertaEditarPerfil = document.getElementById('contAlertaEditarPerfil')
        
        formEditarPerfil.addEventListener('submit', (event) => {
            event.preventDefault()
            HttpCtrl.postGeneral(event, '/perfiles/postEditar', botonEditarPerfil, contAlertaEditarPerfil)
        })
    }

    return {
        eventosCrearPerfil: eventosCrearPerfil,
        eventosEditarPerfil: eventosEditarPerfil
    }
})(HttpCtrl)