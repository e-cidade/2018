<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: Escola
//CLASSE DA ENTIDADE sala
class cl_sala {
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
   var $ed16_i_codigo = 0;
   var $ed16_i_escola = 0;
   var $ed16_i_tiposala = 0;
   var $ed16_c_descr = null;
   var $ed16_i_capacidade = 0;
   var $ed16_c_pertence = null;
   var $ed16_f_metragem = 0;
   var $ed16_i_calculoaluno = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed16_i_codigo = int8 = Código da Sala
                 ed16_i_escola = int8 = Escola
                 ed16_i_tiposala = int8 = Tipo de Dependência
                 ed16_c_descr = char(20) = Descrição
                 ed16_i_capacidade = int4 = Capacidade
                 ed16_c_pertence = char(1) = Própria
                 ed16_f_metragem = float4 = Medida da Sala em m2
                 ed16_i_calculoaluno = int4 = Capacidade Calculada
                 ";
   //funcao construtor da classe
   function cl_sala() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sala");
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
       $this->ed16_i_codigo = ($this->ed16_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed16_i_codigo"]:$this->ed16_i_codigo);
       $this->ed16_i_escola = ($this->ed16_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed16_i_escola"]:$this->ed16_i_escola);
       $this->ed16_i_tiposala = ($this->ed16_i_tiposala == ""?@$GLOBALS["HTTP_POST_VARS"]["ed16_i_tiposala"]:$this->ed16_i_tiposala);
       $this->ed16_c_descr = ($this->ed16_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed16_c_descr"]:$this->ed16_c_descr);
       $this->ed16_i_capacidade = ($this->ed16_i_capacidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed16_i_capacidade"]:$this->ed16_i_capacidade);
       $this->ed16_c_pertence = ($this->ed16_c_pertence == ""?@$GLOBALS["HTTP_POST_VARS"]["ed16_c_pertence"]:$this->ed16_c_pertence);
       $this->ed16_f_metragem = ($this->ed16_f_metragem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed16_f_metragem"]:$this->ed16_f_metragem);
       $this->ed16_i_calculoaluno = ($this->ed16_i_calculoaluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed16_i_calculoaluno"]:$this->ed16_i_calculoaluno);
     }else{
       $this->ed16_i_codigo = ($this->ed16_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed16_i_codigo"]:$this->ed16_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed16_i_codigo){
      $this->atualizacampos();
     if($this->ed16_i_escola == null ){
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed16_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed16_i_tiposala == null ){
       $this->erro_sql = " Campo Tipo de Dependência nao Informado.";
       $this->erro_campo = "ed16_i_tiposala";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed16_c_descr == null ){
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ed16_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed16_i_capacidade == null ){
       $this->ed16_i_capacidade = "null";
     }
     if($this->ed16_c_pertence == null ){
       $this->erro_sql = " Campo Própria nao Informado.";
       $this->erro_campo = "ed16_c_pertence";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed16_f_metragem == null ){
       $this->ed16_f_metragem = "0";
     }
     if($this->ed16_i_calculoaluno == null ){
       $this->ed16_i_calculoaluno = "0";
     }
     if($ed16_i_codigo == "" || $ed16_i_codigo == null ){
       $result = db_query("select nextval('sala_ed16_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sala_ed16_i_codigo_seq do campo: ed16_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed16_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from sala_ed16_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed16_i_codigo)){
         $this->erro_sql = " Campo ed16_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed16_i_codigo = $ed16_i_codigo;
       }
     }
     if(($this->ed16_i_codigo == null) || ($this->ed16_i_codigo == "") ){
       $this->erro_sql = " Campo ed16_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sala(
                                       ed16_i_codigo
                                      ,ed16_i_escola
                                      ,ed16_i_tiposala
                                      ,ed16_c_descr
                                      ,ed16_i_capacidade
                                      ,ed16_c_pertence
                                      ,ed16_f_metragem
                                      ,ed16_i_calculoaluno
                       )
                values (
                                $this->ed16_i_codigo
                               ,$this->ed16_i_escola
                               ,$this->ed16_i_tiposala
                               ,'$this->ed16_c_descr'
                               ,$this->ed16_i_capacidade
                               ,'$this->ed16_c_pertence'
                               ,$this->ed16_f_metragem
                               ,$this->ed16_i_calculoaluno
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Salas da escola ($this->ed16_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Salas da escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Salas da escola ($this->ed16_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed16_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed16_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008237,'$this->ed16_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010039,1008237,'','".AddSlashes(pg_result($resaco,0,'ed16_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010039,1008238,'','".AddSlashes(pg_result($resaco,0,'ed16_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010039,1008239,'','".AddSlashes(pg_result($resaco,0,'ed16_i_tiposala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010039,1008240,'','".AddSlashes(pg_result($resaco,0,'ed16_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010039,1008241,'','".AddSlashes(pg_result($resaco,0,'ed16_i_capacidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010039,1008242,'','".AddSlashes(pg_result($resaco,0,'ed16_c_pertence'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010039,13457,'','".AddSlashes(pg_result($resaco,0,'ed16_f_metragem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010039,13458,'','".AddSlashes(pg_result($resaco,0,'ed16_i_calculoaluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed16_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update sala set ";
     $virgula = "";
     if(trim($this->ed16_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed16_i_codigo"])){
       $sql  .= $virgula." ed16_i_codigo = $this->ed16_i_codigo ";
       $virgula = ",";
       if(trim($this->ed16_i_codigo) == null ){
         $this->erro_sql = " Campo Código da Sala nao Informado.";
         $this->erro_campo = "ed16_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed16_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed16_i_escola"])){
       $sql  .= $virgula." ed16_i_escola = $this->ed16_i_escola ";
       $virgula = ",";
       if(trim($this->ed16_i_escola) == null ){
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed16_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed16_i_tiposala)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed16_i_tiposala"])){
       $sql  .= $virgula." ed16_i_tiposala = $this->ed16_i_tiposala ";
       $virgula = ",";
       if(trim($this->ed16_i_tiposala) == null ){
         $this->erro_sql = " Campo Tipo de Dependência nao Informado.";
         $this->erro_campo = "ed16_i_tiposala";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed16_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed16_c_descr"])){
       $sql  .= $virgula." ed16_c_descr = '$this->ed16_c_descr' ";
       $virgula = ",";
       if(trim($this->ed16_c_descr) == null ){
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ed16_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed16_i_capacidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed16_i_capacidade"])){
        if(trim($this->ed16_i_capacidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed16_i_capacidade"])){
           $this->ed16_i_capacidade = "null" ;
        }
       $sql  .= $virgula." ed16_i_capacidade = $this->ed16_i_capacidade ";
       $virgula = ",";
     }
     if(trim($this->ed16_c_pertence)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed16_c_pertence"])){
       $sql  .= $virgula." ed16_c_pertence = '$this->ed16_c_pertence' ";
       $virgula = ",";
       if(trim($this->ed16_c_pertence) == null ){
         $this->erro_sql = " Campo Própria nao Informado.";
         $this->erro_campo = "ed16_c_pertence";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed16_f_metragem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed16_f_metragem"])){
        if(trim($this->ed16_f_metragem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed16_f_metragem"])){
           $this->ed16_f_metragem = "null" ;
        }
       $sql  .= $virgula." ed16_f_metragem = $this->ed16_f_metragem ";
       $virgula = ",";
     }
     if(trim($this->ed16_i_calculoaluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed16_i_calculoaluno"])){
        if(trim($this->ed16_i_calculoaluno)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed16_i_calculoaluno"])){
           $this->ed16_i_calculoaluno = "null" ;
        }
       $sql  .= $virgula." ed16_i_calculoaluno = $this->ed16_i_calculoaluno ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed16_i_codigo!=null){
       $sql .= " ed16_i_codigo = $this->ed16_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed16_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008237,'$this->ed16_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed16_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010039,1008237,'".AddSlashes(pg_result($resaco,$conresaco,'ed16_i_codigo'))."','$this->ed16_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed16_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,1010039,1008238,'".AddSlashes(pg_result($resaco,$conresaco,'ed16_i_escola'))."','$this->ed16_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed16_i_tiposala"]))
           $resac = db_query("insert into db_acount values($acount,1010039,1008239,'".AddSlashes(pg_result($resaco,$conresaco,'ed16_i_tiposala'))."','$this->ed16_i_tiposala',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed16_c_descr"]))
           $resac = db_query("insert into db_acount values($acount,1010039,1008240,'".AddSlashes(pg_result($resaco,$conresaco,'ed16_c_descr'))."','$this->ed16_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed16_i_capacidade"]))
           $resac = db_query("insert into db_acount values($acount,1010039,1008241,'".AddSlashes(pg_result($resaco,$conresaco,'ed16_i_capacidade'))."','$this->ed16_i_capacidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed16_c_pertence"]))
           $resac = db_query("insert into db_acount values($acount,1010039,1008242,'".AddSlashes(pg_result($resaco,$conresaco,'ed16_c_pertence'))."','$this->ed16_c_pertence',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed16_f_metragem"]))
           $resac = db_query("insert into db_acount values($acount,1010039,13457,'".AddSlashes(pg_result($resaco,$conresaco,'ed16_f_metragem'))."','$this->ed16_f_metragem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed16_i_calculoaluno"]))
           $resac = db_query("insert into db_acount values($acount,1010039,13458,'".AddSlashes(pg_result($resaco,$conresaco,'ed16_i_calculoaluno'))."','$this->ed16_i_calculoaluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Salas da escola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed16_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Salas da escola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed16_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed16_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008237,'$ed16_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010039,1008237,'','".AddSlashes(pg_result($resaco,$iresaco,'ed16_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010039,1008238,'','".AddSlashes(pg_result($resaco,$iresaco,'ed16_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010039,1008239,'','".AddSlashes(pg_result($resaco,$iresaco,'ed16_i_tiposala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010039,1008240,'','".AddSlashes(pg_result($resaco,$iresaco,'ed16_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010039,1008241,'','".AddSlashes(pg_result($resaco,$iresaco,'ed16_i_capacidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010039,1008242,'','".AddSlashes(pg_result($resaco,$iresaco,'ed16_c_pertence'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010039,13457,'','".AddSlashes(pg_result($resaco,$iresaco,'ed16_f_metragem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010039,13458,'','".AddSlashes(pg_result($resaco,$iresaco,'ed16_i_calculoaluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sala
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed16_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed16_i_codigo = $ed16_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Salas da escola nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed16_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Salas da escola nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:sala";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed16_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sala ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = sala.ed16_i_escola";
     $sql .= "      inner join tiposala  on  tiposala.ed14_i_codigo = sala.ed16_i_tiposala";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      left join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join censoorgreg  on  censoorgreg.ed263_i_codigo = escola.ed18_i_censoorgreg";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql2 = "";
     if($dbwhere==""){
       if($ed16_i_codigo!=null ){
         $sql2 .= " where sala.ed16_i_codigo = $ed16_i_codigo ";
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
   function sql_query_file ( $ed16_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sala ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed16_i_codigo!=null ){
         $sql2 .= " where sala.ed16_i_codigo = $ed16_i_codigo ";
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
?>