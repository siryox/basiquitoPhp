$(document).ready(function(){   

/*  VALIDACIONES USADAS EN MAESTTROS */
    //para numero de placas
    $('.alfa_placa').keyup(function (){
        this.value = (this.value + '').replace(/[`~!@#%^&*()._$°¬|+\=?;:'¨´"< ¡>\{\}\{¿,[\]\\\/]/gi,'');
    });
    //para nombres propios de componentes
    $('.alfa_desc').keyup(function (){
        this.value = (this.value + '').replace(/[`~!@#%^$¡¨¿*()_°¬|+\=?;:'"<>\{\}\[\]\\\/]/gi,'');
    });
    //para direcciones de domicilio
    $('.alfa_dir').keyup(function (){
        this.value = (this.value + '').replace(/[`~!@%^&$¡¨¿*_¬|+\=?;:'"<>\{\}\[\]\\\/]/gi,'');
    });
    //para corre electronico
    $('.alfa_correo').keyup(function (){
        this.value = (this.value + '').replace(/[`~!#%^&$¡¨¿*()°¬|+\=?;:'"<>\{\}\[\]\\\/]/gi,'');
    });
    //para nombre propios
    $('.alfa_nom').keyup(function (){
        this.value = (this.value + '').replace(/[0-9`~!@#$%^&*()_°¬|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
    });
    
    //para clave de usuario
    $('.clave').keyup(function (){
        this.value = (this.value + '').replace(/[`~!@#$%^&*()_°¬|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
    });
    
    //para descricpion de requisitos
    $('.alfa_requi').keyup(function (){
        this.value = (this.value + '').replace(/[`~!@#$%^&*()_¬|+\=?;:'"<>{\}\[\]\\\/]/gi, '');
    });
    $('.alfa').keyup(function (){
        this.value = (this.value + '').replace(/[0-9`~!@#$%^&*()_°¬|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
    });        this.value = (this.value + '').replace(/[`~!@#$%^&*()_°¬|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');

    $('.alfa_num').keyup(function (){
        this.value = (this.value + '').replace(/[`~!@#$%^&*()_°¬|+\-=?;:,._ç´`+*/¡'"<>\{\}\[\]\\\/]/gi, '');
    });    
    //para numero telefonicos y fax
    $('.num_tel').keyup(function (){
        this.value = (this.value + '').replace(/[^0-9-]/gi,'');
        
    });
    //para numero entero
    $('.num_entero').keyup(function (){
        this.value = (this.value + '').replace(/[^0-9]/g, '');
    });
    //para cantidad en presentacion
    $('.num_real').keyup(function (){
        this.value = (this.value + '').replace(/[^0-9.]/g, '');
    });
    // validaciones para numeros decimales  
    $(document).on('keyup','.num_deci',function (){
        this.value = (this.value + '').replace(/[^0-9`,.]/g, '');
    });
    //validaciones para numeros enteros
    $('.num').keyup(function (){
        this.value = (this.value + '').replace(/[^0-9]/g, '');
    });
    $('.decimal').keyup(function (){
        this.value = (this.value + '').replace(/[^0-9`,.]/g, '');
    });
    $('.rif').keyup(function (){
        this.value = (this.value + '').replace(/[^0-9`-]/g, '');
    });
/****** FIN DE VALIRDACIONES USADAS EN MAESTEROS***************/    
/*---------------------------------------------------------------------*/    
       $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
        if(e.which == 13) {
            e.preventDefault();
            return false;
        }
    });  
    
});