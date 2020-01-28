<?
//MODULO: biblioteca
//CLASSE DA ENTIDADE acervo
class cl_acervo {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $bi06_seq = 0;
   var $bi06_biblioteca = 0;
   var $bi06_dataregistro_dia = null;
   var $bi06_dataregistro_mes = null;
   var $bi06_dataregistro_ano = null;
   var $bi06_dataregistro = null;
   var $bi06_edicao = null;
   var $bi06_titulo = null;
   var $bi06_classcdd = null;
   var $bi06_isbn = null;
   var $bi06_volume = 0;
   var $bi06_tipoitem = 0;
   var $bi06_editora = 0;
   var $bi06_classiliteraria = 0;
   var $bi06_anoedicao = 0;
   var $bi06_colecaoacervo = 0;
   var $bi06_cutter = null;
   var $bi06_idioma = 0;
   var $bi06_subtitulo = null;
   var $bi06_titulooriginal = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 bi06_seq = int8 = Código do Acervo
                 bi06_biblioteca = int8 = Biblioteca
                 bi06_dataregistro = date = Data de Registro
                 bi06_edicao = char(10) = Edição
                 bi06_titulo = char(100) = Título
                 bi06_classcdd = char(30) = Classificação C.D.D
                 bi06_isbn = char(30) = I.S.B.N
                 bi06_volume = int8 = Volume
                 bi06_tipoitem = int8 = Tipo do Item
                 bi06_editora = int8 = Editora
                 bi06_classiliteraria = int8 = Classificação Literária
                 bi06_anoedicao = int4 = Ano da Edição
                 bi06_colecaoacervo = int4 = Sequencial
                 bi06_cutter = varchar(30) = Código Cutter
                 bi06_idioma = int4 = Idioma
                 bi06_subtitulo = varchar(100) = Subtítulo
                 bi06_titulooriginal = varchar(100) = Título Original
                 ";
   //funcao construtor da classe
   function cl_acervo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acervo");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->bi06_seq = ($this->bi06_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_seq"]:$this->bi06_seq);
       $this->bi06_biblioteca = ($this->bi06_biblioteca == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_biblioteca"]:$this->bi06_biblioteca);
       if($this->bi06_dataregistro == ""){
         $this->bi06_dataregistro_dia = ($this->bi06_dataregistro_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_dataregistro_dia"]:$this->bi06_dataregistro_dia);
         $this->bi06_dataregistro_mes = ($this->bi06_dataregistro_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_dataregistro_mes"]:$this->bi06_dataregistro_mes);
         $this->bi06_dataregistro_ano = ($this->bi06_dataregistro_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_dataregistro_ano"]:$this->bi06_dataregistro_ano);
         if($this->bi06_dataregistro_dia != ""){
            $this->bi06_dataregistro = $this->bi06_dataregistro_ano."-".$this->bi06_dataregistro_mes."-".$this->bi06_dataregistro_dia;
         }
       }
       $this->bi06_edicao = ($this->bi06_edicao == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_edicao"]:$this->bi06_edicao);
       $this->bi06_titulo = ($this->bi06_titulo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_titulo"]:$this->bi06_titulo);
       $this->bi06_classcdd = ($this->bi06_classcdd == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_classcdd"]:$this->bi06_classcdd);
       $this->bi06_isbn = ($this->bi06_isbn == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_isbn"]:$this->bi06_isbn);
       $this->bi06_volume = ($this->bi06_volume == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_volume"]:$this->bi06_volume);
       $this->bi06_tipoitem = ($this->bi06_tipoitem == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_tipoitem"]:$this->bi06_tipoitem);
       $this->bi06_editora = ($this->bi06_editora == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_editora"]:$this->bi06_editora);
       $this->bi06_classiliteraria = ($this->bi06_classiliteraria == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_classiliteraria"]:$this->bi06_classiliteraria);
       $this->bi06_anoedicao = ($this->bi06_anoedicao == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_anoedicao"]:$this->bi06_anoedicao);
       $this->bi06_colecaoacervo = ($this->bi06_colecaoacervo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_colecaoacervo"]:$this->bi06_colecaoacervo);
       $this->bi06_cutter = ($this->bi06_cutter == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_cutter"]:$this->bi06_cutter);
       $this->bi06_idioma = ($this->bi06_idioma == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_idioma"]:$this->bi06_idioma);
       $this->bi06_subtitulo = ($this->bi06_subtitulo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_subtitulo"]:$this->bi06_subtitulo);
       $this->bi06_titulooriginal = ($this->bi06_titulooriginal == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_titulooriginal"]:$this->bi06_titulooriginal);
     }else{
       $this->bi06_seq = ($this->bi06_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["bi06_seq"]:$this->bi06_seq);
     }
   }
   // funcao para Inclusão
   function incluir ($bi06_seq){
      $this->atualizacampos();
     if($this->bi06_biblioteca == null ){
       $this->erro_sql = " Campo Biblioteca não informado.";
       $this->erro_campo = "bi06_biblioteca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi06_dataregistro == null ){
       $this->erro_sql = " Campo Data de Registro não informado.";
       $this->erro_campo = "bi06_dataregistro_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi06_edicao == null ){
       $this->erro_sql = " Campo Edição não informado.";
       $this->erro_campo = "bi06_edicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi06_titulo == null ){
       $this->erro_sql = " Campo Título não informado.";
       $this->erro_campo = "bi06_titulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi06_volume == null ){
       $this->bi06_volume = "0";
     }
     if($this->bi06_tipoitem == null ){
       $this->erro_sql = " Campo Tipo do Item não informado.";
       $this->erro_campo = "bi06_tipoitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi06_editora == null ){
       $this->erro_sql = " Campo Editora não informado.";
       $this->erro_campo = "bi06_editora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi06_classiliteraria == null ){
       $this->erro_sql = " Campo Classificação Literária não informado.";
       $this->erro_campo = "bi06_classiliteraria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi06_anoedicao == null ){
       $this->erro_sql = " Campo Ano da Edição não informado.";
       $this->erro_campo = "bi06_anoedicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi06_colecaoacervo == null ){
       $this->bi06_colecaoacervo = "null";
     }
     if($this->bi06_idioma == null ){
       $this->erro_sql = " Campo Idioma não informado.";
       $this->erro_campo = "bi06_idioma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bi06_seq == "" || $bi06_seq == null ){
       $result = db_query("select nextval('acervo_bi06_seq_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acervo_bi06_seq_seq do campo: bi06_seq";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->bi06_seq = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from acervo_bi06_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi06_seq)){
         $this->erro_sql = " Campo bi06_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi06_seq = $bi06_seq;
       }
     }
     if(($this->bi06_seq == null) || ($this->bi06_seq == "") ){
       $this->erro_sql = " Campo bi06_seq não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acervo(
                                       bi06_seq
                                      ,bi06_biblioteca
                                      ,bi06_dataregistro
                                      ,bi06_edicao
                                      ,bi06_titulo
                                      ,bi06_classcdd
                                      ,bi06_isbn
                                      ,bi06_volume
                                      ,bi06_tipoitem
                                      ,bi06_editora
                                      ,bi06_classiliteraria
                                      ,bi06_anoedicao
                                      ,bi06_colecaoacervo
                                      ,bi06_cutter
                                      ,bi06_idioma
                                      ,bi06_subtitulo
                                      ,bi06_titulooriginal
                       )
                values (
                                $this->bi06_seq
                               ,$this->bi06_biblioteca
                               ,".($this->bi06_dataregistro == "null" || $this->bi06_dataregistro == ""?"null":"'".$this->bi06_dataregistro."'")."
                               ,'$this->bi06_edicao'
                               ,'$this->bi06_titulo'
                               ,'$this->bi06_classcdd'
                               ,'$this->bi06_isbn'
                               ,$this->bi06_volume
                               ,$this->bi06_tipoitem
                               ,$this->bi06_editora
                               ,$this->bi06_classiliteraria
                               ,$this->bi06_anoedicao
                               ,$this->bi06_colecaoacervo
                               ,'$this->bi06_cutter'
                               ,$this->bi06_idioma
                               ,'$this->bi06_subtitulo'
                               ,'$this->bi06_titulooriginal'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acervo ($this->bi06_seq) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acervo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acervo ($this->bi06_seq) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->bi06_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->bi06_seq  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008163,'$this->bi06_seq','I')");
         $resac = db_query("insert into db_acount values($acount,1008014,1008163,'','".AddSlashes(pg_result($resaco,0,'bi06_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,1008161,'','".AddSlashes(pg_result($resaco,0,'bi06_biblioteca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,1008111,'','".AddSlashes(pg_result($resaco,0,'bi06_dataregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,1008113,'','".AddSlashes(pg_result($resaco,0,'bi06_edicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,1008114,'','".AddSlashes(pg_result($resaco,0,'bi06_titulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,1008115,'','".AddSlashes(pg_result($resaco,0,'bi06_classcdd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,1008116,'','".AddSlashes(pg_result($resaco,0,'bi06_isbn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,1008118,'','".AddSlashes(pg_result($resaco,0,'bi06_volume'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,1008122,'','".AddSlashes(pg_result($resaco,0,'bi06_tipoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,1008123,'','".AddSlashes(pg_result($resaco,0,'bi06_editora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,1008124,'','".AddSlashes(pg_result($resaco,0,'bi06_classiliteraria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,19583,'','".AddSlashes(pg_result($resaco,0,'bi06_anoedicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,19994,'','".AddSlashes(pg_result($resaco,0,'bi06_colecaoacervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,21639,'','".AddSlashes(pg_result($resaco,0,'bi06_cutter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,21927,'','".AddSlashes(pg_result($resaco,0,'bi06_idioma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,22346,'','".AddSlashes(pg_result($resaco,0,'bi06_subtitulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008014,22345,'','".AddSlashes(pg_result($resaco,0,'bi06_titulooriginal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($bi06_seq=null) {
      $this->atualizacampos();
     $sql = " update acervo set ";
     $virgula = "";
     if(trim($this->bi06_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_seq"])){
       $sql  .= $virgula." bi06_seq = $this->bi06_seq ";
       $virgula = ",";
       if(trim($this->bi06_seq) == null ){
         $this->erro_sql = " Campo Código do Acervo não informado.";
         $this->erro_campo = "bi06_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi06_biblioteca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_biblioteca"])){
       $sql  .= $virgula." bi06_biblioteca = $this->bi06_biblioteca ";
       $virgula = ",";
       if(trim($this->bi06_biblioteca) == null ){
         $this->erro_sql = " Campo Biblioteca não informado.";
         $this->erro_campo = "bi06_biblioteca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi06_dataregistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_dataregistro_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi06_dataregistro_dia"] !="") ){
       $sql  .= $virgula." bi06_dataregistro = '$this->bi06_dataregistro' ";
       $virgula = ",";
       if(trim($this->bi06_dataregistro) == null ){
         $this->erro_sql = " Campo Data de Registro não informado.";
         $this->erro_campo = "bi06_dataregistro_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi06_dataregistro_dia"])){
         $sql  .= $virgula." bi06_dataregistro = null ";
         $virgula = ",";
         if(trim($this->bi06_dataregistro) == null ){
           $this->erro_sql = " Campo Data de Registro não informado.";
           $this->erro_campo = "bi06_dataregistro_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bi06_edicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_edicao"])){
       $sql  .= $virgula." bi06_edicao = '$this->bi06_edicao' ";
       $virgula = ",";
       if(trim($this->bi06_edicao) == null ){
         $this->erro_sql = " Campo Edição não informado.";
         $this->erro_campo = "bi06_edicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi06_titulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_titulo"])){
       $sql  .= $virgula." bi06_titulo = '$this->bi06_titulo' ";
       $virgula = ",";
       if(trim($this->bi06_titulo) == null ){
         $this->erro_sql = " Campo Título não informado.";
         $this->erro_campo = "bi06_titulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi06_classcdd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_classcdd"])){
       $sql  .= $virgula." bi06_classcdd = '$this->bi06_classcdd' ";
       $virgula = ",";
     }
     if(trim($this->bi06_isbn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_isbn"])){
       $sql  .= $virgula." bi06_isbn = '$this->bi06_isbn' ";
       $virgula = ",";
     }
     if(trim($this->bi06_volume)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_volume"])){
        if(trim($this->bi06_volume)=="" && isset($GLOBALS["HTTP_POST_VARS"]["bi06_volume"])){
           $this->bi06_volume = "0" ;
        }
       $sql  .= $virgula." bi06_volume = $this->bi06_volume ";
       $virgula = ",";
     }
     if(trim($this->bi06_tipoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_tipoitem"])){
       $sql  .= $virgula." bi06_tipoitem = $this->bi06_tipoitem ";
       $virgula = ",";
       if(trim($this->bi06_tipoitem) == null ){
         $this->erro_sql = " Campo Tipo do Item não informado.";
         $this->erro_campo = "bi06_tipoitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi06_editora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_editora"])){
       $sql  .= $virgula." bi06_editora = $this->bi06_editora ";
       $virgula = ",";
       if(trim($this->bi06_editora) == null ){
         $this->erro_sql = " Campo Editora não informado.";
         $this->erro_campo = "bi06_editora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi06_classiliteraria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_classiliteraria"])){
       $sql  .= $virgula." bi06_classiliteraria = $this->bi06_classiliteraria ";
       $virgula = ",";
       if(trim($this->bi06_classiliteraria) == null ){
         $this->erro_sql = " Campo Classificação Literária não informado.";
         $this->erro_campo = "bi06_classiliteraria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi06_anoedicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_anoedicao"])){
       $sql  .= $virgula." bi06_anoedicao = $this->bi06_anoedicao ";
       $virgula = ",";
       if(trim($this->bi06_anoedicao) == null ){
         $this->erro_sql = " Campo Ano da Edição não informado.";
         $this->erro_campo = "bi06_anoedicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi06_colecaoacervo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_colecaoacervo"])){
        if(trim($this->bi06_colecaoacervo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["bi06_colecaoacervo"])){
           $this->bi06_colecaoacervo = 'null';
        }
       $sql  .= $virgula." bi06_colecaoacervo = $this->bi06_colecaoacervo ";
       $virgula = ",";
     }
     if(trim($this->bi06_cutter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_cutter"])){
       $sql  .= $virgula." bi06_cutter = '$this->bi06_cutter' ";
       $virgula = ",";
     }
     if(trim($this->bi06_idioma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_idioma"])){
       $sql  .= $virgula." bi06_idioma = $this->bi06_idioma ";
       $virgula = ",";
       if(trim($this->bi06_idioma) == null ){
         $this->erro_sql = " Campo Idioma não informado.";
         $this->erro_campo = "bi06_idioma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi06_subtitulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_subtitulo"])){
       $sql  .= $virgula." bi06_subtitulo = '$this->bi06_subtitulo' ";
       $virgula = ",";
     }
     if(trim($this->bi06_titulooriginal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi06_titulooriginal"])){
       $sql  .= $virgula." bi06_titulooriginal = '$this->bi06_titulooriginal' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($bi06_seq!=null){
       $sql .= " bi06_seq = $this->bi06_seq";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->bi06_seq));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008163,'$this->bi06_seq','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_seq"]) || $this->bi06_seq != "")
             $resac = db_query("insert into db_acount values($acount,1008014,1008163,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_seq'))."','$this->bi06_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_biblioteca"]) || $this->bi06_biblioteca != "")
             $resac = db_query("insert into db_acount values($acount,1008014,1008161,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_biblioteca'))."','$this->bi06_biblioteca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_dataregistro"]) || $this->bi06_dataregistro != "")
             $resac = db_query("insert into db_acount values($acount,1008014,1008111,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_dataregistro'))."','$this->bi06_dataregistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_edicao"]) || $this->bi06_edicao != "")
             $resac = db_query("insert into db_acount values($acount,1008014,1008113,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_edicao'))."','$this->bi06_edicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_titulo"]) || $this->bi06_titulo != "")
             $resac = db_query("insert into db_acount values($acount,1008014,1008114,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_titulo'))."','$this->bi06_titulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_classcdd"]) || $this->bi06_classcdd != "")
             $resac = db_query("insert into db_acount values($acount,1008014,1008115,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_classcdd'))."','$this->bi06_classcdd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_isbn"]) || $this->bi06_isbn != "")
             $resac = db_query("insert into db_acount values($acount,1008014,1008116,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_isbn'))."','$this->bi06_isbn',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_volume"]) || $this->bi06_volume != "")
             $resac = db_query("insert into db_acount values($acount,1008014,1008118,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_volume'))."','$this->bi06_volume',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_tipoitem"]) || $this->bi06_tipoitem != "")
             $resac = db_query("insert into db_acount values($acount,1008014,1008122,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_tipoitem'))."','$this->bi06_tipoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_editora"]) || $this->bi06_editora != "")
             $resac = db_query("insert into db_acount values($acount,1008014,1008123,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_editora'))."','$this->bi06_editora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_classiliteraria"]) || $this->bi06_classiliteraria != "")
             $resac = db_query("insert into db_acount values($acount,1008014,1008124,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_classiliteraria'))."','$this->bi06_classiliteraria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_anoedicao"]) || $this->bi06_anoedicao != "")
             $resac = db_query("insert into db_acount values($acount,1008014,19583,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_anoedicao'))."','$this->bi06_anoedicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_colecaoacervo"]) || $this->bi06_colecaoacervo != "")
             $resac = db_query("insert into db_acount values($acount,1008014,19994,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_colecaoacervo'))."','$this->bi06_colecaoacervo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_cutter"]) || $this->bi06_cutter != "")
             $resac = db_query("insert into db_acount values($acount,1008014,21639,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_cutter'))."','$this->bi06_cutter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_idioma"]) || $this->bi06_idioma != "")
             $resac = db_query("insert into db_acount values($acount,1008014,21927,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_idioma'))."','$this->bi06_idioma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_subtitulo"]) || $this->bi06_subtitulo != "")
             $resac = db_query("insert into db_acount values($acount,1008014,22346,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_subtitulo'))."','$this->bi06_subtitulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi06_titulooriginal"]) || $this->bi06_titulooriginal != "")
             $resac = db_query("insert into db_acount values($acount,1008014,22345,'".AddSlashes(pg_result($resaco,$conresaco,'bi06_titulooriginal'))."','$this->bi06_titulooriginal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acervo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi06_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Acervo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi06_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->bi06_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($bi06_seq=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($bi06_seq));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008163,'$bi06_seq','E')");
           $resac  = db_query("insert into db_acount values($acount,1008014,1008163,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,1008161,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_biblioteca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,1008111,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_dataregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,1008113,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_edicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,1008114,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_titulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,1008115,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_classcdd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,1008116,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_isbn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,1008118,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_volume'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,1008122,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_tipoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,1008123,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_editora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,1008124,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_classiliteraria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,19583,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_anoedicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,19994,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_colecaoacervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,21639,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_cutter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,21927,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_idioma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,22346,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_subtitulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008014,22345,'','".AddSlashes(pg_result($resaco,$iresaco,'bi06_titulooriginal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from acervo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($bi06_seq)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " bi06_seq = $bi06_seq ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acervo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi06_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Acervo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi06_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$bi06_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:acervo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($bi06_seq = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from acervo ";
     $sql .= "      left  join colecaoacervo  on  colecaoacervo.bi29_sequencial = acervo.bi06_colecaoacervo";
     $sql .= "      inner join idioma  on  idioma.bi22_sequencial = acervo.bi06_idioma";
     $sql .= "      inner join editora  on  editora.bi02_codigo = acervo.bi06_editora";
     $sql .= "      inner join classiliteraria  on  classiliteraria.bi03_codigo = acervo.bi06_classiliteraria";
     $sql .= "      inner join tipoitem  on  tipoitem.bi05_codigo = acervo.bi06_tipoitem";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = acervo.bi06_biblioteca";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = biblioteca.bi17_coddepto";
     $sql .= "      left join localacervo  on  localacervo.bi20_acervo = acervo.bi06_seq";
     $sql .= "      left join localizacao  on  localizacao.bi09_codigo = localacervo.bi20_localizacao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($bi06_seq)) {
         $sql2 .= " where acervo.bi06_seq = $bi06_seq ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql
   public function sql_query_file ($bi06_seq = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from acervo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($bi06_seq)){
         $sql2 .= " where acervo.bi06_seq = $bi06_seq ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

   function sql_query_autores ( $bi06_seq=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from acervo ";
     $sql .= "      inner join editora  on  editora.bi02_codigo = acervo.bi06_editora";
     $sql .= "      inner join classiliteraria  on  classiliteraria.bi03_codigo = acervo.bi06_classiliteraria";
     $sql .= "      inner join tipoitem  on  tipoitem.bi05_codigo = acervo.bi06_tipoitem";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = acervo.bi06_biblioteca";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = biblioteca.bi17_coddepto";
     $sql .= "      inner join autoracervo  on  autoracervo.bi21_acervo = acervo.bi06_seq";
     $sql .= "      left  join colecaoacervo  on  colecaoacervo.bi29_sequencial = acervo.bi06_colecaoacervo";
     $sql .= "      left join localacervo  on  localacervo.bi20_acervo = acervo.bi06_seq";
     $sql .= "      left join localizacao  on  localizacao.bi09_codigo = localacervo.bi20_localizacao";
     $sql2 = "";
     if($dbwhere==""){
       if($bi06_seq!=null ){
         $sql2 .= " where acervo.bi06_seq = $bi06_seq ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
