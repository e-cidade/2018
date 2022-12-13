
/**
 * Representa um campo de digitação de valor monetario
 * 
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @version  $Revision: 1.2 $
 *
 */
(function(exports, DBInput) {

  var DBInputFoto = function () {

    this.type             = 'hidden';
    
    this.inputFileElement = document.createElement("input");
    
    this.imgElement       = new Image();

    this.fileReader       = new FileReader();
    this.fileReader.addEventListener('load', function(event){
      this.setValue(event.target.result);
    }.bind(this));

    
    
    return DBInput.apply(this, arguments);
  };

  /**
   * Registrando Herança
   */
  DBInputFoto.prototype = Object.create(DBInput.prototype, {
        
    '__infect' : DBInput.extend(function() {
      this.setValue(this.inputElement.value);
      this.montarInputFile();

      this.montarImagem();
      DBInput.prototype.__infect.apply(this, arguments);
    }),

    'callbackUpload' : DBInput.extend(function(dados) {
      
      var caminho = dados.caminho_upload;

      if(dados.erro) {
        
        alert("Erro ao enviar a imagem ao servidor. \n" + dados.mensagem);
        caminho = '';
      }
      this.setValue(caminho);

    }),

    'setValue' : DBInput.extend(function(value) {

      this.carregarFoto(value); 
      DBInput.prototype.setValue.apply(this, arguments);
      
      this.inputElement.dispatchEvent(new Event("change"));
    }),

    /**
     * Monta os comportamentos do campo
     */
    'montarImagem' : DBInput.extend(function(){

      this.imgElement.addEventListener("click", function(event){
        this.inputFileElement.click();
      }.bind(this));

      this.inputElement.parentNode.appendChild(this.imgElement);
    }),


    /**
     * Rendiza as informações
     */
    'montarInputFile' : DBInput.extend(function() {

      this.inputFileElement.type          = 'file';
      this.inputFileElement.style.display = 'none';
      this.inputFileElement.name          = 'imagem';
      this.inputFileElement.readOnly      = this.inputElement.readOnly; // Clonado do elemento atual
      this.inputFileElement.disabled      = this.inputElement.disabled; // Clonado do elemento atual
      this.inputFileElement.accept        = this.inputElement.accept || ".jpeg, .jpg, .png";

      this.inputFileElement.addEventListener('change', function(event) {
        
        if(!this.inputFileElement.files || !this.inputFileElement.files[0]) {
          return this.setValue('');
        }


        this.fileReader.readAsDataURL(
          this.inputFileElement.files[0]
        );

        this.enviarFoto();

      }.bind(this));

      this.inputElement.parentNode.appendChild(this.inputFileElement);
    }),

    'carregarFoto' : DBInput.extend(function(foto) {

      if (!foto) {
        return this.imgElement.src = 'imagens/none1.jpeg';
      }

      var regexNumero = /^\d+$/;
      
      foto = ('' + foto).trim();

      if (!regexNumero.test(foto)) {

        this.inputElement.value = foto;
        return this.imgElement.src = foto;
      } else {
        this.importarFoto(+foto);
      }
    }),

    'importarFoto' : DBInput.extend(function(oid) {

       this.inputElement.value = oid;
       this.imgElement.src = 'func_mostrarimagem.php?oid=' + oid;
    }),

    'enviarFoto' : DBInput.extend(function() {
      
      AjaxRequest.create(
        "DBFileUpload.php", 
        {"tipo":"ajax"}, 
        this.callbackUpload.bind(this)
      ).addFileInput(this.inputFileElement)
       .setMessage("Salvando Foto...")
       .execute();
    })
  });
  
      
  DBInputFoto.prototype.constructor = DBInputFoto;

  /**
   * Registrando módulo na memoria para execução
   */
  exports.DBInputFoto = DBInputFoto;
  return DBInputFoto;

})(this, DBInput);
