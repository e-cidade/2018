<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: farmacia
//CLASSE DA ENTIDADE far_matersaude
class cl_far_matersaude {
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
   var $fa01_i_codigo = 0;
   var $fa01_t_obs = null;
   var $fa01_i_codmater = 0;
   var $fa01_i_class = 0;
   var $fa01_c_nomegenerico = null;
   var $fa01_i_medanvisa = 0;
   var $fa01_i_prescricaomed = 0;
   var $fa01_i_classemed = 0;
   var $fa01_i_listacontroladomed = 0;
   var $fa01_i_medrefemed = 0;
   var $fa01_i_laboratoriomed = 0;
   var $fa01_i_formafarmaceuticamed = 0;
   var $fa01_i_concentracaomed = 0;
   var $fa01_i_medhiperdia = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 fa01_i_codigo = int4 = C�digo
                 fa01_t_obs = text = Observa��o
                 fa01_i_codmater = int8 = Medicamento
                 fa01_i_class = int4 = Classifica��o
                 fa01_c_nomegenerico = char(40) = Nome Gen�rico
                 fa01_i_medanvisa = int4 = Medicamento Anvisa
                 fa01_i_prescricaomed = int4 = Prescri��o M�dica
                 fa01_i_classemed = int4 = Classe Terap�utica
                 fa01_i_listacontroladomed = int4 = Lista Controlados
                 fa01_i_medrefemed = int4 = Medicamento Refer�ncia
                 fa01_i_laboratoriomed = int4 = Laborat�rio
                 fa01_i_formafarmaceuticamed = int4 = Forma Farmac�utica
                 fa01_i_concentracaomed = int4 = Concentra��o
                 fa01_i_medhiperdia = int4 = Hiperdia
                 ";
   //funcao construtor da classe
   function cl_far_matersaude() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_matersaude");
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
       $this->fa01_i_codigo = ($this->fa01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_codigo"]:$this->fa01_i_codigo);
       $this->fa01_t_obs = ($this->fa01_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_t_obs"]:$this->fa01_t_obs);
       $this->fa01_i_codmater = ($this->fa01_i_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_codmater"]:$this->fa01_i_codmater);
       $this->fa01_i_class = ($this->fa01_i_class == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_class"]:$this->fa01_i_class);
       $this->fa01_c_nomegenerico = ($this->fa01_c_nomegenerico == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_c_nomegenerico"]:$this->fa01_c_nomegenerico);
       $this->fa01_i_medanvisa = ($this->fa01_i_medanvisa == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_medanvisa"]:$this->fa01_i_medanvisa);
       $this->fa01_i_prescricaomed = ($this->fa01_i_prescricaomed == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_prescricaomed"]:$this->fa01_i_prescricaomed);
       $this->fa01_i_classemed = ($this->fa01_i_classemed == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_classemed"]:$this->fa01_i_classemed);
       $this->fa01_i_listacontroladomed = ($this->fa01_i_listacontroladomed == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_listacontroladomed"]:$this->fa01_i_listacontroladomed);
       $this->fa01_i_medrefemed = ($this->fa01_i_medrefemed == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_medrefemed"]:$this->fa01_i_medrefemed);
       $this->fa01_i_laboratoriomed = ($this->fa01_i_laboratoriomed == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_laboratoriomed"]:$this->fa01_i_laboratoriomed);
       $this->fa01_i_formafarmaceuticamed = ($this->fa01_i_formafarmaceuticamed == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_formafarmaceuticamed"]:$this->fa01_i_formafarmaceuticamed);
       $this->fa01_i_concentracaomed = ($this->fa01_i_concentracaomed == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_concentracaomed"]:$this->fa01_i_concentracaomed);
       $this->fa01_i_medhiperdia = ($this->fa01_i_medhiperdia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_medhiperdia"]:$this->fa01_i_medhiperdia);
     }else{
       $this->fa01_i_codigo = ($this->fa01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_codigo"]:$this->fa01_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa01_i_codigo){
      $this->atualizacampos();
     if($this->fa01_i_codmater == null ){
       $this->erro_sql = " Campo Medicamento nao Informado.";
       $this->erro_campo = "fa01_i_codmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa01_i_class == null ){
       $this->erro_sql = " Campo Classifica��o nao Informado.";
       $this->erro_campo = "fa01_i_class";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
    if($this->fa01_i_medanvisa == null ){
       $this->fa01_i_medanvisa = "null";
     }
     if($this->fa01_i_prescricaomed == null ){
       $this->fa01_i_prescricaomed = "null";
     }
     if($this->fa01_i_classemed == null ){
       $this->fa01_i_classemed = "null";
     }
     if($this->fa01_i_listacontroladomed == null ){
       $this->fa01_i_listacontroladomed = "null";
     }
     if($this->fa01_i_medrefemed == null ){
       $this->fa01_i_medrefemed = "null";
     }
     if($this->fa01_i_laboratoriomed == null ){
       $this->fa01_i_laboratoriomed = "null";
     }
     if($this->fa01_i_formafarmaceuticamed == null ){
       $this->fa01_i_formafarmaceuticamed = "null";
     }
     if($this->fa01_i_concentracaomed == null ){
       $this->fa01_i_concentracaomed = "null";
     }

     if($this->fa01_i_medhiperdia == null ){
       $this->erro_sql = " Campo Hiperdia nao Informado.";
       $this->erro_campo = "fa01_i_medhiperdia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($fa01_i_codigo == "" || $fa01_i_codigo == null ){
       $result = db_query("select nextval('farmatersaude_fa01_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: farmatersaude_fa01_i_codigo_seq do campo: fa01_i_codigo";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->fa01_i_codigo = pg_result($result,0,0);
     }else{
      //$result = db_query("select last_value from farmatersaude_fa01_i_codigo_seq");
       //if(($result != false) && (pg_result($result,0,0) < $fa01_i_codigo)){
       //  $this->erro_sql = " Campo fa01_i_codigo maior que �ltimo n�mero da sequencia.";
       //  $this->erro_banco = "Sequencia menor que este n�mero.";
       //  $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       //  $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       //  $this->erro_status = "0";
       //  return false;
       //}else{
         $this->fa01_i_codigo = $fa01_i_codigo;
       //}
     }
     if(($this->fa01_i_codigo == null) || ($this->fa01_i_codigo == "") ){
       $this->erro_sql = " Campo fa01_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_matersaude(
                                       fa01_i_codigo
                                      ,fa01_t_obs
                                      ,fa01_i_codmater
                                      ,fa01_i_class
                                      ,fa01_c_nomegenerico
                                      ,fa01_i_medanvisa
                                      ,fa01_i_prescricaomed
                                      ,fa01_i_classemed
                                      ,fa01_i_listacontroladomed
                                      ,fa01_i_medrefemed
                                      ,fa01_i_laboratoriomed
                                      ,fa01_i_formafarmaceuticamed
                                      ,fa01_i_concentracaomed
                                      ,fa01_i_medhiperdia
                       )
                values (
                                $this->fa01_i_codigo
                               ,'$this->fa01_t_obs'
                               ,$this->fa01_i_codmater
                               ,$this->fa01_i_class
                               ,'$this->fa01_c_nomegenerico'
                               ,$this->fa01_i_medanvisa
                               ,$this->fa01_i_prescricaomed
                               ,$this->fa01_i_classemed
                               ,$this->fa01_i_listacontroladomed
                               ,$this->fa01_i_medrefemed
                               ,$this->fa01_i_laboratoriomed
                               ,$this->fa01_i_formafarmaceuticamed
                               ,$this->fa01_i_concentracaomed
                               ,$this->fa01_i_medhiperdia
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_matersaude ($this->fa01_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_matersaude j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_matersaude ($this->fa01_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_sql .= "Valores : ".$this->fa01_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa01_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12117,'$this->fa01_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2104,12117,'','".AddSlashes(pg_result($resaco,0,'fa01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,12118,'','".AddSlashes(pg_result($resaco,0,'fa01_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,12120,'','".AddSlashes(pg_result($resaco,0,'fa01_i_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,12119,'','".AddSlashes(pg_result($resaco,0,'fa01_i_class'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,14017,'','".AddSlashes(pg_result($resaco,0,'fa01_c_nomegenerico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,14019,'','".AddSlashes(pg_result($resaco,0,'fa01_i_medanvisa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,14459,'','".AddSlashes(pg_result($resaco,0,'fa01_i_prescricaomed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,14458,'','".AddSlashes(pg_result($resaco,0,'fa01_i_classemed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,14457,'','".AddSlashes(pg_result($resaco,0,'fa01_i_listacontroladomed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,14456,'','".AddSlashes(pg_result($resaco,0,'fa01_i_medrefemed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,14455,'','".AddSlashes(pg_result($resaco,0,'fa01_i_laboratoriomed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,14454,'','".AddSlashes(pg_result($resaco,0,'fa01_i_formafarmaceuticamed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,14453,'','".AddSlashes(pg_result($resaco,0,'fa01_i_concentracaomed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2104,17275,'','".AddSlashes(pg_result($resaco,0,'fa01_i_medhiperdia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($fa01_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update far_matersaude set ";
     $virgula = "";
     if(trim($this->fa01_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_codigo"])){
       $sql  .= $virgula." fa01_i_codigo = $this->fa01_i_codigo ";
       $virgula = ",";
       if(trim($this->fa01_i_codigo) == null ){
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "fa01_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa01_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_t_obs"])){
       $sql  .= $virgula." fa01_t_obs = '$this->fa01_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->fa01_i_codmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_codmater"])){
       $sql  .= $virgula." fa01_i_codmater = $this->fa01_i_codmater ";
       $virgula = ",";
       if(trim($this->fa01_i_codmater) == null ){
         $this->erro_sql = " Campo Medicamento nao Informado.";
         $this->erro_campo = "fa01_i_codmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa01_i_class)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_class"])){
       $sql  .= $virgula." fa01_i_class = $this->fa01_i_class ";
       $virgula = ",";
       if(trim($this->fa01_i_class) == null ){
         $this->erro_sql = " Campo Classifica��o nao Informado.";
         $this->erro_campo = "fa01_i_class";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa01_c_nomegenerico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_c_nomegenerico"])){
       $sql  .= $virgula." fa01_c_nomegenerico = '$this->fa01_c_nomegenerico' ";
       $virgula = ",";
     }
   if(trim($this->fa01_i_medanvisa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_medanvisa"])){
        if(trim($this->fa01_i_medanvisa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_medanvisa"])){
           $this->fa01_i_medanvisa = "null" ;
        }
       $sql  .= $virgula." fa01_i_medanvisa = $this->fa01_i_medanvisa ";
       $virgula = ",";
     }
     if(trim($this->fa01_i_prescricaomed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_prescricaomed"])){
        if(trim($this->fa01_i_prescricaomed)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_prescricaomed"])){
           $this->fa01_i_prescricaomed = "null" ;
        }
       $sql  .= $virgula." fa01_i_prescricaomed = $this->fa01_i_prescricaomed ";
       $virgula = ",";
     }
     if(trim($this->fa01_i_classemed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_classemed"])){
        if(trim($this->fa01_i_classemed)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_classemed"])){
           $this->fa01_i_classemed = "null" ;
        }
       $sql  .= $virgula." fa01_i_classemed = $this->fa01_i_classemed ";
       $virgula = ",";
     }
     if(trim($this->fa01_i_listacontroladomed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_listacontroladomed"])){
        if(trim($this->fa01_i_listacontroladomed)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_listacontroladomed"])){
           $this->fa01_i_listacontroladomed = "null" ;
        }
       $sql  .= $virgula." fa01_i_listacontroladomed = $this->fa01_i_listacontroladomed ";
       $virgula = ",";
     }
     if(trim($this->fa01_i_medrefemed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_medrefemed"])){
        if(trim($this->fa01_i_medrefemed)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_medrefemed"])){
           $this->fa01_i_medrefemed = "null" ;
        }
       $sql  .= $virgula." fa01_i_medrefemed = $this->fa01_i_medrefemed ";
       $virgula = ",";
     }
     if(trim($this->fa01_i_laboratoriomed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_laboratoriomed"])){
        if(trim($this->fa01_i_laboratoriomed)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_laboratoriomed"])){
           $this->fa01_i_laboratoriomed = "null" ;
        }
       $sql  .= $virgula." fa01_i_laboratoriomed = $this->fa01_i_laboratoriomed ";
       $virgula = ",";
     }
     if(trim($this->fa01_i_formafarmaceuticamed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_formafarmaceuticamed"])){
        if(trim($this->fa01_i_formafarmaceuticamed)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_formafarmaceuticamed"])){
           $this->fa01_i_formafarmaceuticamed = "null" ;
        }
       $sql  .= $virgula." fa01_i_formafarmaceuticamed = $this->fa01_i_formafarmaceuticamed ";
       $virgula = ",";
     }
     if(trim($this->fa01_i_concentracaomed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_concentracaomed"])){
        if(trim($this->fa01_i_concentracaomed)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_concentracaomed"])){
           $this->fa01_i_concentracaomed = "null" ;
        }
       $sql  .= $virgula." fa01_i_concentracaomed = $this->fa01_i_concentracaomed ";
       $virgula = ",";
     }
     if(trim($this->fa01_i_medhiperdia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_medhiperdia"])){
       $sql  .= $virgula." fa01_i_medhiperdia = $this->fa01_i_medhiperdia ";
       $virgula = ",";
       if(trim($this->fa01_i_medhiperdia) == null ){
         $this->erro_sql = " Campo Hiperdia nao Informado.";
         $this->erro_campo = "fa01_i_medhiperdia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa01_i_codigo!=null){
       $sql .= " fa01_i_codigo = $this->fa01_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa01_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12117,'$this->fa01_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_codigo"]) || $this->fa01_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2104,12117,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_i_codigo'))."','$this->fa01_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_t_obs"]) || $this->fa01_t_obs != "")
           $resac = db_query("insert into db_acount values($acount,2104,12118,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_t_obs'))."','$this->fa01_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_codmater"]) || $this->fa01_i_codmater != "")
           $resac = db_query("insert into db_acount values($acount,2104,12120,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_i_codmater'))."','$this->fa01_i_codmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_class"]) || $this->fa01_i_class != "")
           $resac = db_query("insert into db_acount values($acount,2104,12119,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_i_class'))."','$this->fa01_i_class',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_c_nomegenerico"]) || $this->fa01_c_nomegenerico != "")
           $resac = db_query("insert into db_acount values($acount,2104,14017,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_c_nomegenerico'))."','$this->fa01_c_nomegenerico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_medanvisa"]) || $this->fa01_i_medanvisa != "")
           $resac = db_query("insert into db_acount values($acount,2104,14019,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_i_medanvisa'))."','$this->fa01_i_medanvisa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_prescricaomed"]) || $this->fa01_i_prescricaomed != "")
           $resac = db_query("insert into db_acount values($acount,2104,14459,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_i_prescricaomed'))."','$this->fa01_i_prescricaomed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_classemed"]) || $this->fa01_i_classemed != "")
           $resac = db_query("insert into db_acount values($acount,2104,14458,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_i_classemed'))."','$this->fa01_i_classemed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_listacontroladomed"]) || $this->fa01_i_listacontroladomed != "")
           $resac = db_query("insert into db_acount values($acount,2104,14457,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_i_listacontroladomed'))."','$this->fa01_i_listacontroladomed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_medrefemed"]) || $this->fa01_i_medrefemed != "")
           $resac = db_query("insert into db_acount values($acount,2104,14456,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_i_medrefemed'))."','$this->fa01_i_medrefemed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_laboratoriomed"]) || $this->fa01_i_laboratoriomed != "")
           $resac = db_query("insert into db_acount values($acount,2104,14455,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_i_laboratoriomed'))."','$this->fa01_i_laboratoriomed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_formafarmaceuticamed"]) || $this->fa01_i_formafarmaceuticamed != "")
           $resac = db_query("insert into db_acount values($acount,2104,14454,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_i_formafarmaceuticamed'))."','$this->fa01_i_formafarmaceuticamed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_concentracaomed"]) || $this->fa01_i_concentracaomed != "")
           $resac = db_query("insert into db_acount values($acount,2104,14453,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_i_concentracaomed'))."','$this->fa01_i_concentracaomed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa01_i_medhiperdia"]) || $this->fa01_i_medhiperdia != "")
           $resac = db_query("insert into db_acount values($acount,2104,17275,'".AddSlashes(pg_result($resaco,$conresaco,'fa01_i_medhiperdia'))."','$this->fa01_i_medhiperdia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");

       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_matersaude nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa01_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_matersaude nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($fa01_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa01_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12117,'$fa01_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2104,12117,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,12118,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,12120,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_i_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,12119,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_i_class'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,14017,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_c_nomegenerico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,14019,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_i_medanvisa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,14459,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_i_prescricaomed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,14458,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_i_classemed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,14457,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_i_listacontroladomed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,14456,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_i_medrefemed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,14455,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_i_laboratoriomed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,14454,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_i_formafarmaceuticamed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,14453,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_i_concentracaomed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2104,17275,'','".AddSlashes(pg_result($resaco,$iresaco,'fa01_i_medhiperdia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_matersaude
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa01_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa01_i_codigo = $fa01_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_matersaude nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa01_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_matersaude nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:far_matersaude";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
 // funcao do sql
   function sql_query ( $fa01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from far_matersaude ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
     $sql .= "      left join far_class  on  far_class.fa05_i_codigo = far_matersaude.fa01_i_class";
     $sql .= "      left  join far_medanvisa  on  far_medanvisa.fa14_i_codigo = far_matersaude.fa01_i_medanvisa";
     $sql .= "      left  join far_prescricaomed  on  far_prescricaomed.fa31_i_codigo = far_matersaude.fa01_i_prescricaomed";
     $sql .= "      left  join far_laboratoriomed  on  far_laboratoriomed.fa32_i_codigo = far_matersaude.fa01_i_laboratoriomed";
     $sql .= "      left  join far_formafarmaceuticamed  on  far_formafarmaceuticamed.fa33_i_codigo = far_matersaude.fa01_i_formafarmaceuticamed";
     $sql .= "      left  join far_medreferenciamed  on  far_medreferenciamed.fa34_i_codigo = far_matersaude.fa01_i_medrefemed";
     $sql .= "      left  join far_listacontroladomed  on  far_listacontroladomed.fa35_i_codigo = far_matersaude.fa01_i_listacontroladomed";
     $sql .= "      left  join far_classeterapeuticamed  on  far_classeterapeuticamed.fa36_i_codigo = far_matersaude.fa01_i_classemed";
     $sql .= "      left  join far_concentracaomed  on  far_concentracaomed.fa37_i_codigo = far_matersaude.fa01_i_concentracaomed";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join far_medicamentohiperdia  on  far_medicamentohiperdia.fa43_i_codigo = far_matersaude.fa01_i_medhiperdia";
     $sql .= "      left join far_medanvisa  as a on   a.fa14_i_codigo = far_prescricaomed.fa31_i_medanvisa";
     $sql .= "      left join far_prescricaomedica  on  far_prescricaomedica.fa20_i_codigo = far_prescricaomed.fa31_i_prescricao";
     $sql .= "      left join far_medanvisa  as bb on   bb.fa14_i_codigo = far_laboratoriomed.fa32_i_medanvisa";
     $sql .= "      left join far_laboratorio  as c on   c.fa24_i_codigo = far_laboratoriomed.fa32_i_laboratorio";
     $sql .= "      left join far_medanvisa  as p on   p.fa14_i_codigo = far_formafarmaceuticamed.fa33_i_medanvisa";
     $sql .= "      left join far_formafarmaceutica  as d on   d.fa29_i_codigo = far_formafarmaceuticamed.fa33_i_formafarmaceutica";
     $sql .= "      left join far_medanvisa  as o on   o.fa14_i_codigo = far_medreferenciamed.fa34_i_medanvisa";
     $sql .= "      left join far_medreferencia  as z on   z.fa19_i_codigo = far_medreferenciamed.fa34_i_medreferencia";
     $sql .= "      left join far_medanvisa  as r on   r.fa14_i_codigo = far_listacontroladomed.fa35_i_medanvisa";
     $sql .= "      left join far_listacontrolado  as l on   l.fa15_i_codigo = far_listacontroladomed.fa35_i_listacontrolado";
     $sql .= "      left join far_medanvisa  as m on   m.fa14_i_codigo = far_classeterapeuticamed.fa36_i_medanvisa";
     $sql .= "      left join far_classeterapeutica  as b on   b.fa18_i_codigo = far_classeterapeuticamed.fa36_i_classeterapeutica";
     $sql .= "      left join far_medanvisa  as w on   w.fa14_i_codigo = far_concentracaomed.fa37_i_medanvisa";
     $sql .= "      left join far_concentracao  as q on   q.fa30_i_codigo = far_concentracaomed.fa37_i_concentracao";
     $sql2 = "";
     if($dbwhere==""){
       if($fa01_i_codigo!=null ){
         $sql2 .= " where far_matersaude.fa01_i_codigo = $fa01_i_codigo ";
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
// funcao do sql
   function sql_query_tipo ( $fa01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select distinct ";
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
     $sql .= " from far_matersaude ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
     $sql .= "      left join far_class  on  far_class.fa05_i_codigo = far_matersaude.fa01_i_class";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join far_listacontroladomed  on  far_listacontroladomed.fa35_i_codigo = far_matersaude.fa01_i_listacontroladomed";
	 $sql .= "      inner join far_listacontrolado  on  far_listacontrolado.fa15_i_codigo = far_listacontroladomed.fa35_i_listacontrolado";
     $sql .= "      inner join far_listaprescricao  on  far_listaprescricao.fa21_i_listacontrolado = far_listacontrolado.fa15_i_codigo";
     $sql .= "      inner join far_prescricaomed  on  far_prescricaomed.fa31_i_prescricao = far_listaprescricao.fa21_i_prescricaomedica";
     $sql .= "      inner join far_prescricaomedica  on  far_prescricaomedica.fa20_i_codigo = far_prescricaomed.fa31_i_prescricao";
     $sql2 = "";
     if($dbwhere==""){
       if($fa01_i_codigo!=null ){
         $sql2 .= " where far_matersaude.fa01_i_codigo = $fa01_i_codigo ";
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
   // funcao do sql
   function sql_query_file ( $fa01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from far_matersaude ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
     $sql2 = "";
     if($dbwhere==""){
       if($fa01_i_codigo!=null ){
         $sql2 .= " where far_matersaude.fa01_i_codigo = $fa01_i_codigo ";
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

  function sql_query_atendrequiitem ( $m43_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from atendrequiitem ";
     $sql .= "      inner join matrequiitem         on  matrequiitem.m41_codigo = atendrequiitem.m43_codmatrequiitem";
     $sql .= "      inner join atendrequi           on  atendrequi.m42_codigo   = atendrequiitem.m43_codatendrequi";
     $sql .= "      inner join matmater             on  matmater.m60_codmater   = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matunid              on  matmater.m60_codmatunid = matunid.m61_codmatunid";
     $sql .= "      inner join matrequi             on  matrequi.m40_codigo     = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join matestoqueinimeiari  on  m49_codatendrequiitem   = m43_codigo";
     $sql .= "      inner join matestoqueinimei     on  m49_codmatestoqueinimei = m82_codigo";
     $sql .= "      inner join matestoqueitem       on  m71_codlanc             = m82_matestoqueitem";
     $sql .= "      inner join matestoque           on  m70_codigo              = m71_codmatestoque";
     $sql2 = "";
     if($dbwhere==""){
       if($m43_codigo!=null ){
         $sql2 .= " where atendrequiitem.m43_codigo = $m43_codigo ";
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
  function sql_query_matmater ( $m60_codmater=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from matmater ";
     $sql .= "   inner join matunid on matunid.m61_codmatunid = matmater.m60_codmatunid ";
     $sql .= "   inner join far_matersaude on fa01_i_codmater = m60_codmater ";
     $sql .= "   inner join matestoque on m70_codmatmater = m60_codmater ";
     $sql .= "   inner join matestoqueitem on m71_codmatestoque = m70_codigo ";
     $sql .= "   inner join matestoqueitemlote      on m77_matestoqueitem = m71_codlanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($m43_codigo!=null ){
         $sql2 .= " where matmater.m60_codmater = $m60_codmater ";
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
  /*Sql utilizado na rotina Relatorio->Medicamento->Medicamentos
   *@autor Matheus Marinho, webseller.matheus.marinho@gmail.com
   *@data 20/03/2012
   */
function sql_query_medicamento ( $fa01_i_codigo=null, $sCampos="*", $sOrdem=null, $dbwhere="") {

     $sSql = "select distinct ";

     if ($sCampos != "*" ) {

       $sCampos_sql = split("#",$sCampos);
       $sVirgula = "";

       for ($i = 0; $i < sizeof($sCampos_sql); $i++) {

         $sSql .= $sVirgula.$sCampos_sql[$i];
         $sVirgula = ",";
       }
     }else{
       $sSql .= $sCampos;
     }
     $sSql .= " from far_matersaude ";
     $sSql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
     $sSql.= "       inner join matmaterestoque on matmaterestoque.m64_matmater = matmater.m60_codmater";
     $sSql .= "      left join far_class  on  far_class.fa05_i_codigo = far_matersaude.fa01_i_class";
     $sSql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sSql .= "      inner join far_listacontroladomed  on  far_listacontroladomed.fa35_i_codigo = far_matersaude.fa01_i_listacontroladomed";
     $sSql .= "      inner join far_listacontrolado  on  far_listacontrolado.fa15_i_codigo = far_listacontroladomed.fa35_i_listacontrolado";
     $sSql .= "      inner join far_listaprescricao  on  far_listaprescricao.fa21_i_listacontrolado = far_listacontrolado.fa15_i_codigo";
     $sSql .= "      inner join far_prescricaomed  on  far_prescricaomed.fa31_i_prescricao = far_listaprescricao.fa21_i_prescricaomedica";
     $sSql .= "      inner join far_prescricaomedica  on  far_prescricaomedica.fa20_i_codigo = far_prescricaomed.fa31_i_prescricao";
     $sSql2 = "";

     if ( $dbwhere=="") {

       if ($fa01_i_codigo != null ) {
         $sSql2 .= " where far_matersaude.fa01_i_codigo = $fa01_i_codigo ";
       }

     }else if ($dbwhere != "") {
       $sSql2 = " where $dbwhere";
     }

     $sSql .= $sSql2;

     if ($sOrdem != null ) {

       $sSql .= " order by ";
       $sCampos_sql = split("#",$sOrdem);
       $sVirgula = "";

       for ($i = 0; $i < sizeof($sCampos_sql); $i++){

         $sSql .= $sVirgula.$sCampos_sql[$i];
         $sVirgula = ",";
       }
     }
     return $sSql;
  }
   /* Sql utilizado na rotina Relatorio->Medicamento->Estoque
   * @autor Adriano Quili�o de Oliveira, adriano.oliveira@dbseller.com.br
   * @data 18/06/2012
   */
  function sql_query_estoque($fa01_i_codigo=null, $sCampos="*", $sOrdem=null, $dbwhere="") {

     $sSql = "select ";
     if ($sCampos != "*") {

       $sCampos_sql = split("#",$sCampos);
       $sVirgula    = "";
       for ($i = 0; $i < sizeof($sCampos_sql); $i++) {

         $sSql     .= $sVirgula.$sCampos_sql[$i];
         $sVirgula  = ",";

       }

     } else {
       $sSql .= $sCampos;
     }
     $sSql .= " from far_matersaude ";
     $sSql .= "      inner join matmater    on matmater.m60_codmater  = far_matersaude.fa01_i_codmater ";
     $sSql .= "      inner join matestoque  on m70_codmatmater        = matmater.m60_codmater ";
     $sSql .= "      inner join db_depart   on db_depart.coddepto     = matestoque.m70_coddepto ";
     $sSql .= "      inner join matunid     on matunid.m61_codmatunid = matmater.m60_codmatunid ";
     $sSql2 = "";
     if ($dbwhere == "") {

       if ($fa01_i_codigo != null ) {
         $sSql2 .= " where far_matersaude.fa01_i_codigo = $fa01_i_codigo ";
       }

     } else if ($dbwhere != "") {
       $sSql2 = " where $dbwhere ";
     }
     $sSql .= $sSql2;
     if ($sOrdem != null) {

       $sSql        .= " order by ";
       $sCampos_sql  = split("#",$sOrdem);
       $sVirgula     = "";
       for ($i = 0; $i < sizeof($sCampos_sql); $i++){

         $sSql    .= $sVirgula.$sCampos_sql[$i];
         $sVirgula = ",";

       }

     }
     return $sSql;

  }

  /* Sql utilizado na rotina Procedimentos > Gera��o Livro de Controlados.
   * Utilizada para buscar o total de Saidas/Entradas de medicamentos.
   * Pode ser utilizada para obter as quantidades referentes a qualquer farm�cia(departamento).
   *
   * @autor Adriano Quili�o de Oliveira, adriano.oliveira@dbseller.com.br
   * @data 22/06/2012
   */
  function sql_query_movimentacaoEstoqueSimples ($fa01_i_codigo=null, $sCampos="*", $sOrdem=null, $dbwhere="") {

     $sSql = "select ";
     if ($sCampos != "*") {

       $sCampos_sql = split("#",$sCampos);
       $sVirgula    = "";
       for ($i = 0; $i < sizeof($sCampos_sql); $i++) {

         $sSql     .= $sVirgula.$sCampos_sql[$i];
         $sVirgula  = ",";

       }

     } else {
       $sSql .= $sCampos;
     }
     $sSql .= " from far_matersaude ";
     $sSql .= "      inner join matestoque       on fa01_i_codmater    = m70_codmatmater    ";
     $sSql .= "      inner join matestoqueitem   on m70_codigo         = m71_codmatestoque  ";
     $sSql .= "      inner join matestoqueinimei on m71_codlanc        = m82_matestoqueitem ";
     $sSql .= "      inner join matestoqueini    on m82_matestoqueini  = m80_codigo         ";
     $sSql .= "      inner join matestoquetipo   on m80_codtipo        = m81_codtipo        ";
     $sSql2 = "";
     if ($dbwhere == "") {

       if ($fa01_i_codigo != null ) {
         $sSql2 .= " where far_matersaude.fa01_i_codigo = $fa01_i_codigo ";
       }

     } else if ($dbwhere != "") {
       $sSql2 = " where $dbwhere ";
     }
     $sSql .= $sSql2;
     if ($sOrdem != null) {

       $sSql        .= " order by ";
       $sCampos_sql  = split("#",$sOrdem);
       $sVirgula     = "";
       for ($i = 0; $i < sizeof($sCampos_sql); $i++){

         $sSql    .= $sVirgula.$sCampos_sql[$i];
         $sVirgula = ",";

       }

     }
     return $sSql;

  }

  /* Sql utilizado na rotina Procedimentos > Gera��o Livro de Controlados.
   * Retorna todas as movimenta��es de estoque de controlados da farm�cia, independente do local
   * onde foi realizada a baixa.
   *
   * @autor Adriano Quili�o de Oliveira, adriano.oliveira@dbseller.com.br
   * @data 26/06/2012
   */
  function sql_query_movimentacaoEstoqueCompleta ($fa01_i_codigo = null, $sCampos="*", $sOrdem = null, $dbwhere = "") {

     $sSql = "select ";
     if ($sCampos != "*") {

       $sCampos_sql = split("#",$sCampos);
       $sVirgula    = "";
       for ($i = 0; $i < sizeof($sCampos_sql); $i++) {

         $sSql     .= $sVirgula.$sCampos_sql[$i];
         $sVirgula  = ",";

       }

     } else {
       $sSql .= $sCampos;
     }

     $sSql .= " from matestoqueini ";
     $sSql .= " inner join matestoqueinimei       on m80_codigo                                = m82_matestoqueini ";
     $sSql .= " inner join matestoqueitem         on m82_matestoqueitem                        = m71_codlanc  ";
     $sSql .= " inner join matestoque             on m70_codigo                                = m71_codmatestoque  ";
     $sSql .= " left  join matestoqueitemlote     on m77_matestoqueitem                        = matestoqueitem.m71_codlanc ";
     /* --REQUISI��ES E ATENDIMENTO DESTAS-- */
     $sSql .= " left  join matestoquetransf        on m83_matestoqueini                        = m80_codigo  ";
     $sSql .= " left  join db_depart as destino    on destino.coddepto                         = m83_coddepto ";
     $sSql .= " left  join matestoqueinimeiari     on m49_codmatestoqueinimei                  = m82_codigo  ";
     $sSql .= " left  join atendrequiitem          on m49_codatendrequiitem                    = m43_codigo  ";
     $sSql .= " left  join matrequiitem            on m41_codigo                               = m43_codmatrequiitem ";
     $sSql .= " left  join matrequi                on m40_codigo                               = m41_codmatrequi ";
     /* --TIPO DE MOVIMENTA��O DE ESTOQUE-- */
     $sSql .= " inner join matestoquetipo         on m80_codtipo                               = m81_codtipo  ";
     /* --DEPARTAMENTO DE ORIGEM | DESCRI��O-- */
     $sSql .= " inner join db_depart               on m70_coddepto                             = db_depart.coddepto ";
     /* --RETIRADA PELA FARM�CIA-- */
     $sSql .= " left  join far_retiradarequi      on far_retiradarequi.fa07_i_matrequi         = m40_codigo ";
     $sSql .= " left  join far_retirada           on far_retirada.fa04_i_codigo                = far_retiradarequi.fa07_i_retirada ";
     $sSql .= " left  join medicos                on medicos.sd03_i_codigo                     = far_retirada.fa04_i_profissional ";
     $sSql .= " left  join cgm                    on cgm.z01_numcgm                            = medicos.sd03_i_cgm";
     $sSql .= " left  join sau_receitamedica      on s158_i_codigo                             = fa04_i_receita ";
     $sSql .= " left  join cgs_und                on cgs_und.z01_i_cgsund                      = far_retirada.fa04_i_cgsund  ";
     /* --USUARIO QUE FEZ A RETIRADA-- */
     $sSql .= " inner join db_usuarios            on id_usuario                                = m80_login ";
     /* --DETALHES DO MATERIAL-- */
     $sSql .= " inner join matmater               on m70_codmatmater                           = m60_codmater  ";
     $sSql .= " inner join far_matersaude         on m60_codmater                              = far_matersaude.fa01_i_codmater ";
     /* --CODIGOS ANVISA E ETC. . */
     $sSql .= " left  join far_medanvisa          on fa14_i_codigo                             = far_matersaude.fa01_i_medanvisa ";
     $sSql .= " left  join far_codigodcb          on fa28_i_medanvisa                          = far_medanvisa.fa14_i_codigo ";
     $sSql .= " left  join far_tipodc             on fa27_i_codigo                             = far_codigodcb.fa28_i_codigo ";
     /* --LISTA DE CONTROLADOS-- */
     $sSql .= " inner join far_listacontroladomed on fa35_i_codigo                             = far_matersaude.fa01_i_listacontroladomed ";
     $sSql .= " inner join far_listacontrolado    on fa15_i_codigo                             = far_listacontroladomed. fa35_i_listacontrolado ";
     $sSql .= " inner join far_listamodelo        on fa17_i_listacontrolado                    = far_listacontrolado.fa15_i_codigo ";

     $sSql2 = "";
     if ($dbwhere == "") {

       if ($fa01_i_codigo != null ) {
         $sSql2 .= " where far_matersaude.fa01_i_codigo = $fa01_i_codigo ";
       }

     } else if ($dbwhere != "") {
       $sSql2 = " where $dbwhere ";
     }
     $sSql .= $sSql2;
     if ($sOrdem != null) {

       $sSql        .= " order by ";
       $sCampos_sql  = split("#",$sOrdem);
       $sVirgula     = "";
       for ($i = 0; $i < sizeof($sCampos_sql); $i++){

         $sSql    .= $sVirgula.$sCampos_sql[$i];
         $sVirgula = ",";

       }

     }
     return $sSql;

  }

}
?>