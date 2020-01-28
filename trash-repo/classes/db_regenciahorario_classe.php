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

//MODULO: escola
//CLASSE DA ENTIDADE regenciahorario
class cl_regenciahorario {
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
   var $ed58_i_codigo = 0;
   var $ed58_i_regencia = 0;
   var $ed58_i_diasemana = 0;
   var $ed58_i_periodo = 0;
   var $ed58_i_rechumano = 0;
   var $ed58_ativo = 'f';
   var $ed58_tipovinculo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed58_i_codigo = int8 = C�digo
                 ed58_i_regencia = int8 = Disciplina
                 ed58_i_diasemana = int8 = Dia da Semana
                 ed58_i_periodo = int8 = Per�odo
                 ed58_i_rechumano = int8 = Regente
                 ed58_ativo = bool = Ativo
                 ed58_tipovinculo = int4 = Forma de V�nculo
                 ";
   //funcao construtor da classe
   function cl_regenciahorario() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("regenciahorario");
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
       $this->ed58_i_codigo = ($this->ed58_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed58_i_codigo"]:$this->ed58_i_codigo);
       $this->ed58_i_regencia = ($this->ed58_i_regencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed58_i_regencia"]:$this->ed58_i_regencia);
       $this->ed58_i_diasemana = ($this->ed58_i_diasemana == ""?@$GLOBALS["HTTP_POST_VARS"]["ed58_i_diasemana"]:$this->ed58_i_diasemana);
       $this->ed58_i_periodo = ($this->ed58_i_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed58_i_periodo"]:$this->ed58_i_periodo);
       $this->ed58_i_rechumano = ($this->ed58_i_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed58_i_rechumano"]:$this->ed58_i_rechumano);
       $this->ed58_ativo = ($this->ed58_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed58_ativo"]:$this->ed58_ativo);
       $this->ed58_tipovinculo = ($this->ed58_tipovinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed58_tipovinculo"]:$this->ed58_tipovinculo);
     }else{
       $this->ed58_i_codigo = ($this->ed58_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed58_i_codigo"]:$this->ed58_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed58_i_codigo){
      $this->atualizacampos();
     if($this->ed58_i_regencia == null ){
       $this->erro_sql = " Campo Disciplina nao Informado.";
       $this->erro_campo = "ed58_i_regencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed58_i_diasemana == null ){
       $this->erro_sql = " Campo Dia da Semana nao Informado.";
       $this->erro_campo = "ed58_i_diasemana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed58_i_periodo == null ){
       $this->erro_sql = " Campo Per�odo nao Informado.";
       $this->erro_campo = "ed58_i_periodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed58_i_rechumano == null ){
       $this->erro_sql = " Campo Regente nao Informado.";
       $this->erro_campo = "ed58_i_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed58_ativo == null ){
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "ed58_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed58_tipovinculo == null ){
       $this->erro_sql = " Campo Forma de V�nculo nao Informado.";
       $this->erro_campo = "ed58_tipovinculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed58_i_codigo == "" || $ed58_i_codigo == null ){
       $result = db_query("select nextval('regenciahorario_ed58_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: regenciahorario_ed58_i_codigo_seq do campo: ed58_i_codigo";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed58_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from regenciahorario_ed58_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed58_i_codigo)){
         $this->erro_sql = " Campo ed58_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed58_i_codigo = $ed58_i_codigo;
       }
     }
     if(($this->ed58_i_codigo == null) || ($this->ed58_i_codigo == "") ){
       $this->erro_sql = " Campo ed58_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into regenciahorario(
                                       ed58_i_codigo
                                      ,ed58_i_regencia
                                      ,ed58_i_diasemana
                                      ,ed58_i_periodo
                                      ,ed58_i_rechumano
                                      ,ed58_ativo
                                      ,ed58_tipovinculo
                       )
                values (
                                $this->ed58_i_codigo
                               ,$this->ed58_i_regencia
                               ,$this->ed58_i_diasemana
                               ,$this->ed58_i_periodo
                               ,$this->ed58_i_rechumano
                               ,'$this->ed58_ativo'
                               ,$this->ed58_tipovinculo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Hor�rio das reg�ncias na turma ($this->ed58_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Hor�rio das reg�ncias na turma j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Hor�rio das reg�ncias na turma ($this->ed58_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed58_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed58_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008558,'$this->ed58_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010099,1008558,'','".AddSlashes(pg_result($resaco,0,'ed58_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010099,1008559,'','".AddSlashes(pg_result($resaco,0,'ed58_i_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010099,1008560,'','".AddSlashes(pg_result($resaco,0,'ed58_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010099,1008561,'','".AddSlashes(pg_result($resaco,0,'ed58_i_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010099,1008562,'','".AddSlashes(pg_result($resaco,0,'ed58_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010099,19239,'','".AddSlashes(pg_result($resaco,0,'ed58_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010099,19767,'','".AddSlashes(pg_result($resaco,0,'ed58_tipovinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed58_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update regenciahorario set ";
     $virgula = "";
     if(trim($this->ed58_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed58_i_codigo"])){
       $sql  .= $virgula." ed58_i_codigo = $this->ed58_i_codigo ";
       $virgula = ",";
       if(trim($this->ed58_i_codigo) == null ){
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed58_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed58_i_regencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed58_i_regencia"])){
       $sql  .= $virgula." ed58_i_regencia = $this->ed58_i_regencia ";
       $virgula = ",";
       if(trim($this->ed58_i_regencia) == null ){
         $this->erro_sql = " Campo Disciplina nao Informado.";
         $this->erro_campo = "ed58_i_regencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed58_i_diasemana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed58_i_diasemana"])){
       $sql  .= $virgula." ed58_i_diasemana = $this->ed58_i_diasemana ";
       $virgula = ",";
       if(trim($this->ed58_i_diasemana) == null ){
         $this->erro_sql = " Campo Dia da Semana nao Informado.";
         $this->erro_campo = "ed58_i_diasemana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed58_i_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed58_i_periodo"])){
       $sql  .= $virgula." ed58_i_periodo = $this->ed58_i_periodo ";
       $virgula = ",";
       if(trim($this->ed58_i_periodo) == null ){
         $this->erro_sql = " Campo Per�odo nao Informado.";
         $this->erro_campo = "ed58_i_periodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed58_i_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed58_i_rechumano"])){
       $sql  .= $virgula." ed58_i_rechumano = $this->ed58_i_rechumano ";
       $virgula = ",";
       if(trim($this->ed58_i_rechumano) == null ){
         $this->erro_sql = " Campo Regente nao Informado.";
         $this->erro_campo = "ed58_i_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed58_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed58_ativo"])){
       $sql  .= $virgula." ed58_ativo = '$this->ed58_ativo' ";
       $virgula = ",";
       if(trim($this->ed58_ativo) == null ){
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "ed58_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed58_tipovinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed58_tipovinculo"])){
       $sql  .= $virgula." ed58_tipovinculo = $this->ed58_tipovinculo ";
       $virgula = ",";
       if(trim($this->ed58_tipovinculo) == null ){
         $this->erro_sql = " Campo Forma de V�nculo nao Informado.";
         $this->erro_campo = "ed58_tipovinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed58_i_codigo!=null){
       $sql .= " ed58_i_codigo = $this->ed58_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed58_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008558,'$this->ed58_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed58_i_codigo"]) || $this->ed58_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1010099,1008558,'".AddSlashes(pg_result($resaco,$conresaco,'ed58_i_codigo'))."','$this->ed58_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed58_i_regencia"]) || $this->ed58_i_regencia != "")
           $resac = db_query("insert into db_acount values($acount,1010099,1008559,'".AddSlashes(pg_result($resaco,$conresaco,'ed58_i_regencia'))."','$this->ed58_i_regencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed58_i_diasemana"]) || $this->ed58_i_diasemana != "")
           $resac = db_query("insert into db_acount values($acount,1010099,1008560,'".AddSlashes(pg_result($resaco,$conresaco,'ed58_i_diasemana'))."','$this->ed58_i_diasemana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed58_i_periodo"]) || $this->ed58_i_periodo != "")
           $resac = db_query("insert into db_acount values($acount,1010099,1008561,'".AddSlashes(pg_result($resaco,$conresaco,'ed58_i_periodo'))."','$this->ed58_i_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed58_i_rechumano"]) || $this->ed58_i_rechumano != "")
           $resac = db_query("insert into db_acount values($acount,1010099,1008562,'".AddSlashes(pg_result($resaco,$conresaco,'ed58_i_rechumano'))."','$this->ed58_i_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed58_ativo"]) || $this->ed58_ativo != "")
           $resac = db_query("insert into db_acount values($acount,1010099,19239,'".AddSlashes(pg_result($resaco,$conresaco,'ed58_ativo'))."','$this->ed58_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed58_tipovinculo"]) || $this->ed58_tipovinculo != "")
           $resac = db_query("insert into db_acount values($acount,1010099,19767,'".AddSlashes(pg_result($resaco,$conresaco,'ed58_tipovinculo'))."','$this->ed58_tipovinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Hor�rio das reg�ncias na turma nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed58_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Hor�rio das reg�ncias na turma nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed58_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed58_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed58_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed58_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008558,'$ed58_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010099,1008558,'','".AddSlashes(pg_result($resaco,$iresaco,'ed58_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010099,1008559,'','".AddSlashes(pg_result($resaco,$iresaco,'ed58_i_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010099,1008560,'','".AddSlashes(pg_result($resaco,$iresaco,'ed58_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010099,1008561,'','".AddSlashes(pg_result($resaco,$iresaco,'ed58_i_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010099,1008562,'','".AddSlashes(pg_result($resaco,$iresaco,'ed58_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010099,19239,'','".AddSlashes(pg_result($resaco,$iresaco,'ed58_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010099,19767,'','".AddSlashes(pg_result($resaco,$iresaco,'ed58_tipovinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from regenciahorario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed58_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed58_i_codigo = $ed58_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Hor�rio das reg�ncias na turma nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed58_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Hor�rio das reg�ncias na turma nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed58_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed58_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:regenciahorario";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
	 function sql_query ( $ed58_i_codigo=null,$campos="*",$ordem=null,$dbwhere="",$sGroupBy =''){
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
     $sql .= " from regenciahorario ";
     $sql .= "      inner join periodoescola  on  periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = regenciahorario.ed58_i_diasemana";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = periodoescola.ed17_i_escola";
     $sql .= "      inner join periodoaula  on  periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = periodoescola.ed17_i_turno";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join caddisciplina on caddisciplina.ed232_i_codigo= disciplina.ed12_i_caddisciplina";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = regencia.ed59_i_serie";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano";
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($ed58_i_codigo!=null ){
         $sql2 .= " where regenciahorario.ed58_i_codigo = $ed58_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
    if( $sGroupBy != null ){
        $sql2 .= " group by $sGroupBy ";
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
   function sql_query_file ( $ed58_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from regenciahorario ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed58_i_codigo!=null ){
         $sql2 .= " where regenciahorario.ed58_i_codigo = $ed58_i_codigo ";
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
   function sql_query_rechumano($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= ' from regenciahorario ';
    $sSql .= '   inner join periodoescola on periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo';
    $sSql .= '   inner join regencia on regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia';
    $sSql .= '   inner join diasemana on diasemana.ed32_i_codigo = regenciahorario.ed58_i_diasemana';
    $sSql .= '   inner join escola on escola.ed18_i_codigo = periodoescola.ed17_i_escola';
    $sSql .= '   inner join periodoaula on periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula';
    $sSql .= '   inner join turno on turno.ed15_i_codigo = periodoescola.ed17_i_turno';
    $sSql .= '   inner join disciplina on disciplina.ed12_i_codigo = regencia.ed59_i_disciplina';
    $sSql .= '   inner join caddisciplina on caddisciplina.ed232_i_codigo= disciplina.ed12_i_caddisciplina';
    $sSql .= '   inner join turma on turma.ed57_i_codigo = regencia.ed59_i_turma';
    $sSql .= '   inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario';
    $sSql .= '   inner join serie on serie.ed11_i_codigo = regencia.ed59_i_serie';
    $sSql .= '   inner join ensino on ensino.ed10_i_codigo = serie.ed11_i_ensino';
    $sSql .= '   inner join rechumano on rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano';
    $sSql .= '   inner join ((select cgm.*, rechumanopessoal.ed284_i_rechumano as rechumano,';
    $sSql .= '                       1 as tipo ';
    $sSql .= '                  from rechumano as a ';
    $sSql .= '                    inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = a.ed20_i_codigo ';
    $sSql .= '                    inner join rhpessoal on rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal';
    $sSql .= '                    inner join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm';
    $sSql .= '                      where rechumanopessoal.ed284_i_rechumano = a.ed20_i_codigo)';
    $sSql .= '                                      union ';
    $sSql .= '               (select cgm.*, rechumanocgm.ed285_i_rechumano as rechumano,';
    $sSql .= '                       2 as tipo ';
    $sSql .= '                 from rechumano as a ';
    $sSql .= '                   inner join rechumanocgm on rechumanocgm.ed285_i_rechumano = a.ed20_i_codigo ';
    $sSql .= '                   inner join cgm on cgm.z01_numcgm = rechumanocgm.ed285_i_cgm';
    $sSql .= '                     where rechumanocgm.ed285_i_rechumano = a.ed20_i_codigo)) as reccgm';
    $sSql .= '     on reccgm.rechumano = rechumano.ed20_i_codigo ';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where regenciahorario.ed58_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }
    }
    return $sSql;
  }

  public function sql_query_diario_classe_periodo($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " from regenciahorario ";
    $sSql .= "      inner join regencia      on ed58_i_regencia      = ed59_i_codigo ";
    $sSql .= "      inner join serie         on ed59_i_serie         = ed11_i_codigo";
    $sSql .= "      inner join turma         on ed59_i_turma         = ed57_i_codigo ";
    $sSql .= "      inner join calendario    on  ed57_i_calendario   = ed52_i_codigo ";
    $sSql .= "      inner join disciplina    on ed59_i_disciplina    = ed12_i_codigo ";
    $sSql .= "      inner join caddisciplina on ed12_i_caddisciplina = ed232_i_codigo ";
    $sSql .= "      inner join rechumano     on ed58_i_rechumano     = ed20_i_codigo ";
    $sSql .= "      inner join periodoescola on ed58_i_periodo       = ed17_i_codigo";
    $sSql .= "      inner join periodoaula   on ed17_i_periodoaula   = ed08_i_codigo";
    $sSql .= "      left join rechumanocgm   on ed285_i_rechumano    = ed58_i_rechumano ";
    $sSql .= "      left join cgm            on ed285_i_cgm          = cgm.z01_numcgm ";
    $sSql .= "      left join rechumanopessoal  on ed284_i_rechumano = ed58_i_rechumano ";
    $sSql .= "      left join rhpessoal         on ed284_i_rhpessoal = rh01_regist ";
    $sSql .= "      left join cgm cgmpessoal    on rh01_numcgm       = cgmpessoal.z01_numcgm ";
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where regenciahorario.ed58_i_codigo = $iCodigo ";
      }
    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }
    }
    return $sSql;
  }
   public function sql_query_diario_classe_matricula($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " from regenciahorario ";
    $sSql .= "      inner join regencia      on ed58_i_regencia      = ed59_i_codigo ";
    $sSql .= "      inner join turma         on ed59_i_turma         = ed57_i_codigo ";
    $sSql .= "      inner join calendario    on  ed57_i_calendario   = ed52_i_codigo ";
    $sSql .= "      inner join disciplina    on ed59_i_disciplina    = ed12_i_codigo ";
    $sSql .= "      inner join caddisciplina on ed12_i_caddisciplina = ed232_i_codigo ";
    $sSql .= "      inner join rechumano     on ed58_i_rechumano     = ed20_i_codigo ";
    $sSql .= "      inner join periodoescola on ed58_i_periodo       = ed17_i_codigo";
    $sSql .= "      inner join periodoaula   on ed17_i_periodoaula   = ed08_i_codigo";
    $sSql .= "      inner join matricula     on ed60_i_turma         = ed57_i_codigo";
    $sSql .= "      inner join aluno         on ed47_i_codigo        = ed60_i_aluno";
    $sSql .= "      left join rechumanocgm   on ed285_i_rechumano    = ed58_i_rechumano ";
    $sSql .= "      left join cgm            on ed285_i_cgm          = cgm.z01_numcgm ";
    $sSql .= "      left join rechumanopessoal  on ed284_i_rechumano = ed58_i_rechumano ";
    $sSql .= "      left join rhpessoal         on ed284_i_rhpessoal = rh01_regist ";
    $sSql .= "      left join cgm cgmpessoal    on rh01_numcgm       = cgmpessoal.z01_numcgm ";
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where regenciahorario.ed58_i_codigo = $iCodigo ";
      }
    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }
    }
    return $sSql;
  }
   function sql_query_regencia_horario_matricula($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " From regencia as regencia_matricula ";
    $sSql .= "      inner join regenciahorario on ed58_i_regencia = regencia_matricula.ed59_i_codigo ";
    $sSql .= "      inner join matricula on regencia_matricula.ed59_i_turma = ed60_i_turma  ";
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql .= " where regenciahorario.ed58_i_codigo = $iCodigo ";
      }
    } elseif ($sDbWhere != '') {
      $sSql .= " where $sDbWhere";
    }

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }
    }
    return $sSql;
  }
   function sql_query_regencia_dia_semana($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= ' from regenciahorario ';
    $sSql .= '   inner join periodoescola on periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo';
    $sSql .= '   inner join regencia on regencia.ed59_i_codigo   = regenciahorario.ed58_i_regencia';
    $sSql .= '   inner join turma         on ed59_i_turma        = ed57_i_codigo ';
    $sSql .= '   inner join diasemana on diasemana.ed32_i_codigo = regenciahorario.ed58_i_diasemana';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql .= " where regenciahorario.ed58_i_codigo = $iCodigo ";
      }
    } elseif ($sDbWhere != '') {
      $sSql .= " where $sDbWhere";
    }

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }
    }
    return $sSql;
  }
   public function sql_query_disciplina_regencia_censo($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " from regenciahorario ";
    $sSql .= "      inner join periodoescola  on  periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo";
    $sSql .= "      inner join regencia  on  regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia";
    $sSql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = regenciahorario.ed58_i_diasemana";
    $sSql .= "      inner join escola  on  escola.ed18_i_codigo = periodoescola.ed17_i_escola";
    $sSql .= "      inner join periodoaula  on  periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula";
    $sSql .= "      inner join turno  on  turno.ed15_i_codigo = periodoescola.ed17_i_turno";
    $sSql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
    $sSql .= "      inner join caddisciplina on caddisciplina.ed232_i_codigo= disciplina.ed12_i_caddisciplina";
    $sSql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
    $sSql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
    $sSql .= "      inner join serie  on  serie.ed11_i_codigo = regencia.ed59_i_serie";
    $sSql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
    $sSql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano";
    $sSql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
    $sSql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
    $sSql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
    $sSql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
    $sSql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
    $sSql .= "      inner join censocaddisciplina on censocaddisciplina.ed294_caddisciplina = caddisciplina.ed232_i_codigo";
    $sSql .= "      inner join censodisciplina    on censodisciplina.ed265_i_codigo         = censocaddisciplina.ed294_censodisciplina ";
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where regenciahorario.ed58_i_codigo = $iCodigo ";
      }
    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }
    }
    return $sSql;
  }
   function sql_query_censo ($ed58_i_codigo=null,$campos="*",$ordem=null,$dbwhere="",$sGroupBy ='') {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from regenciahorario ";
    $sql .= "      inner join periodoescola  on  periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo";
    $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia";
    $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = regenciahorario.ed58_i_diasemana";
    $sql .= "      inner join escola  on  escola.ed18_i_codigo = periodoescola.ed17_i_escola";
    $sql .= "      inner join periodoaula  on  periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula";
    $sql .= "      inner join turno  on  turno.ed15_i_codigo = periodoescola.ed17_i_turno";
    $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
    $sql .= "      inner join caddisciplina on caddisciplina.ed232_i_codigo= disciplina.ed12_i_caddisciplina";
    $sql .= "      inner join censocaddisciplina on censocaddisciplina.ed294_caddisciplina = caddisciplina.ed232_i_codigo";
    $sql .= "      inner join censodisciplina    on censodisciplina.ed265_i_codigo         = censocaddisciplina.ed294_censodisciplina ";
    $sql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
    $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
    $sql .= "      inner join serie  on  serie.ed11_i_codigo = regencia.ed59_i_serie";
    $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
    $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano";
    $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
    $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
    $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
    $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
    $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
    $sql2 = "";
    if ($dbwhere == "") {

      if ($ed58_i_codigo != null) {
        $sql2 .= " where regenciahorario.ed58_i_codigo = $ed58_i_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    if($sGroupBy != null) {
      $sql2 .= " group by $sGroupBy ";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }


  function sql_query_regencia_horario ( $ed323_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {

  	$sql = "select ";
  	if($campos != "*" ) {

  		$campos_sql = split("#",$campos);
  		$virgula = "";
  		for ($i = 0; $i < sizeof($campos_sql); $i++) {

  			$sql .= $virgula.$campos_sql[$i];
  			$virgula = ",";
  		}
  	} else {
  		$sql .= $campos;
  	}
  	$sql .= " from regenciahorario ";
  	$sql .= " left join regenciahorariohistorico on ed323_substituto = ed58_i_rechumano ";
  	$sql .= "                                   and ed323_regencia   = ed58_i_regencia  ";
  	$sql .= "                                   and ed323_periodo    = ed58_i_periodo   ";
  	$sql .= "                                   and ed323_diasemana  = ed58_i_diasemana ";

  	$sql2 = "";
  	if ($dbwhere == "") {

  		if ($ed323_sequencial != null) {
  			$sql2 .= " where regenciahorariohistorico.ed323_sequencial = $ed323_sequencial ";
  		}
  	} else if($dbwhere != "") {
  		$sql2 = " where $dbwhere";
  	}
  	$sql .= $sql2;
  	if($ordem != null ) {

  		$sql       .= " order by ";
  		$campos_sql = split("#",$ordem);
  		$virgula    = "";
  		for ($i = 0; $i < sizeof($campos_sql); $i++) {

  			$sql .= $virgula.$campos_sql[$i];
  			$virgula = ",";
  		}
  	}
  	return $sql;
  }

  function sql_query_regencia_ofertada ( $ed323_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {

  	$sql = "select ";
  	if($campos != "*" ) {

  		$campos_sql = split("#",$campos);
  		$virgula = "";
  		for ($i = 0; $i < sizeof($campos_sql); $i++) {

  			$sql .= $virgula.$campos_sql[$i];
  			$virgula = ",";
  		}
  	} else {
  		$sql .= $campos;
  	}
  	$sql .= " from regenciahorario                                                                             ";
    $sql .= "      inner join regencia      on regencia.ed59_i_codigo      = regenciahorario.ed58_i_regencia   ";
    $sql .= "      inner join periodoescola on periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo    ";
    $sql .= "      inner join turma         on turma.ed57_i_codigo         = regencia.ed59_i_turma             ";
    $sql .= "      inner join base          on base.ed31_i_codigo          = turma.ed57_i_base                 ";
    $sql .= "      inner join cursoedu      on cursoedu.ed29_i_codigo      = base.ed31_i_curso                 ";
  	$sql2 = "";
  	if ($dbwhere == "") {

  		if ($ed323_sequencial != null) {
  			$sql2 .= " where regenciahorariohistorico.ed323_sequencial = $ed323_sequencial ";
  		}
  	} else if($dbwhere != "") {
  		$sql2 = " where $dbwhere";
  	}
  	$sql .= $sql2;
  	if($ordem != null ) {

  		$sql       .= " order by ";
  		$campos_sql = split("#",$ordem);
  		$virgula    = "";
  		for ($i = 0; $i < sizeof($campos_sql); $i++) {

  			$sql .= $virgula.$campos_sql[$i];
  			$virgula = ",";
  		}
  	}
  	return $sql;
  }

  function sql_query_rechumano_regimemat($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

  	$sSql = 'select ';
  	if ($sCampos != '*') {

  		$sCamposSql = split('#', $sCampos);
  		$sVirgula   = '';
  		for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

  			$sSql .= $sVirgula.$sCamposSql[$iCont];
  			$virgula = ",";

  		}

  	} else {
  		$sSql .= $sCampos;
  	}
  	$sSql .= ' from regenciahorario ';
  	$sSql .= '   inner join periodoescola on periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo';
  	$sSql .= '   inner join regencia on regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia';
  	$sSql .= '   inner join diasemana on diasemana.ed32_i_codigo = regenciahorario.ed58_i_diasemana';
  	$sSql .= '   inner join escola on escola.ed18_i_codigo = periodoescola.ed17_i_escola';
  	$sSql .= '   inner join periodoaula on periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula';
  	$sSql .= '   inner join turno on turno.ed15_i_codigo = periodoescola.ed17_i_turno';
  	$sSql .= '   inner join disciplina on disciplina.ed12_i_codigo = regencia.ed59_i_disciplina';
  	$sSql .= '   inner join caddisciplina on caddisciplina.ed232_i_codigo= disciplina.ed12_i_caddisciplina';
  	$sSql .= '   inner join turma on turma.ed57_i_codigo = regencia.ed59_i_turma';
  	$sSql .= '   inner join turmaserieregimemat on turmaserieregimemat.ed220_i_turma = turma.ed57_i_codigo ';
  	$sSql .= '   inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario';
  	$sSql .= '   inner join serie on serie.ed11_i_codigo = regencia.ed59_i_serie';
  	$sSql .= '   inner join ensino on ensino.ed10_i_codigo = serie.ed11_i_ensino';
  	$sSql .= '   inner join rechumano on rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano';
  	$sSql .= '   inner join ((select cgm.*, rechumanopessoal.ed284_i_rechumano as rechumano,';
  	$sSql .= '                       1 as tipo ';
  	$sSql .= '                  from rechumano as a ';
  	$sSql .= '                    inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = a.ed20_i_codigo ';
  	$sSql .= '                    inner join rhpessoal on rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal';
  	$sSql .= '                    inner join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm';
  	$sSql .= '                      where rechumanopessoal.ed284_i_rechumano = a.ed20_i_codigo)';
  	$sSql .= '                                      union ';
  	$sSql .= '               (select cgm.*, rechumanocgm.ed285_i_rechumano as rechumano,';
  	$sSql .= '                       2 as tipo ';
  	$sSql .= '                 from rechumano as a ';
  	$sSql .= '                   inner join rechumanocgm on rechumanocgm.ed285_i_rechumano = a.ed20_i_codigo ';
  	$sSql .= '                   inner join cgm on cgm.z01_numcgm = rechumanocgm.ed285_i_cgm';
  	$sSql .= '                     where rechumanocgm.ed285_i_rechumano = a.ed20_i_codigo)) as reccgm';
  	$sSql .= '     on reccgm.rechumano = rechumano.ed20_i_codigo ';
  	$sSql2 = '';
  	if ($sDbWhere == '') {

  		if ($iCodigo != null ){
  			$sSql2 .= " where regenciahorario.ed58_i_codigo = $iCodigo ";
  		}

  	} elseif ($sDbWhere != '') {
  		$sSql2 = " where $sDbWhere";
  	}
  	$sSql .= $sSql2;

  	if ($sOrdem != null) {

  		$sSql      .= ' order by ';
  		$sCamposSql = split('#', $sOrdem);
  		$sVirgula   = '';
  		for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

  			$sSql    .= $sVirgula.$sCamposSql[$iCont];
  			$sVirgula = ',';

  		}
  	}
  	return $sSql;
  }

  function sql_query_disciplinas_cgm ($sCampos = "*", $sOrdem = null, $sWhereFuncionario = null,
  		                                $sWhereNaoFuncionario = null, $sWhereRegenciaHorario = null,
  		                                $sWhereSubstituto = null, $sWhere = null) {

  	$sql = "select ";
  	if ($sCampos != "*" ) {

  		$campos_sql = split("#",$sCampos);
  		$virgula    = "";
  		for ($i = 0; $i < sizeof($campos_sql); $i++) {

  			$sql     .= $virgula.$campos_sql[$i];
  			$virgula  = ",";
  		}
  	} else {
  		$sql .= $sCampos;
  	}

  	$sSqlRegente  = " select ed20_i_codigo, true::boolean as funcionario                                                      ";
		$sSqlRegente .= "   from rechumano                                                                                        ";
		$sSqlRegente .= "  inner join rechumanoescola  on rechumanoescola.ed75_i_rechumano   = rechumano.ed20_i_codigo            ";
		$sSqlRegente .= "                             and rechumanoescola.ed75_i_saidaescola is null                              ";
		$sSqlRegente .= "  inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo            ";
		$sSqlRegente .= "  inner join rhpessoal        on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal ";
		$sSqlRegente .= "  inner join cgm              on cgm.z01_numcgm                     = rhpessoal.rh01_numcgm              ";

		if (!empty($sWhereFuncionario)) {
			$sSqlRegente .= " where {$sWhereFuncionario} ";
		}

		$sSqlRegente .= " union all                                                                                      ";

		$sSqlRegente .= " select ed20_i_codigo, false::boolean as funcionario                                            ";
		$sSqlRegente .= "   from rechumano                                                                               ";
		$sSqlRegente .= "  inner join rechumanoescola  on rechumanoescola.ed75_i_rechumano   = rechumano.ed20_i_codigo   ";
		$sSqlRegente .= "                             and rechumanoescola.ed75_i_saidaescola is null                     ";
		$sSqlRegente .= "  inner join rechumanocgm     on rechumanocgm.ed285_i_rechumano     = rechumano.ed20_i_codigo   ";
		$sSqlRegente .= "  inner join cgm              on cgm.z01_numcgm                     = rechumanocgm.ed285_i_cgm  ";

		if (!empty($sWhereNaoFuncionario)) {

			$sSqlRegente .= " where {$sWhereNaoFuncionario} ";
		}



		$sql .= "  from  (select distinct regenciahorario.ed58_i_regencia as regencia                                                  ";
		$sql .= "           from ( {$sSqlRegente} ) as regente                                                                         ";
		$sql .= "           inner join regenciahorario           on regenciahorario.ed58_i_rechumano = regente.ed20_i_codigo           ";
		$sql .= "           inner join regencia                  on regencia.ed59_i_codigo           = regenciahorario.ed58_i_regencia ";

		if (!empty($sWhereRegenciaHorario)) {
			$sql .= " where {$sWhereRegenciaHorario} ";

		}

		$sql .= "                                                                                                                ";
		$sql .= "         union all                                                                                              ";
		$sql .= "                                                                                                                ";
		$sql .= "         select distinct docentesubstituto.ed322_regencia as regencia                                           ";
		$sql .= "           from ( {$sSqlRegente} ) as regente                                                                   ";
		$sql .= "           inner join docentesubstituto on docentesubstituto.ed322_rechumano = regente.ed20_i_codigo            ";
		$sql .= "           inner join regenciahorario   on regenciahorario.ed58_i_regencia   = docentesubstituto.ed322_regencia ";
		$sql .= "           inner join regencia          on regencia.ed59_i_codigo            = regenciahorario.ed58_i_regencia  ";
		$sql .= "           inner join turma             on turma.ed57_i_codigo               = regencia.ed59_i_turma            ";
		$sql .= "           inner join calendario        on calendario.ed52_i_codigo          = turma.ed57_i_calendario          ";

		if (!empty($sWhereSubstituto)) {
			$sql .= " where {$sWhereSubstituto} ";
		}

		$sql .= "       ) as regenciasemaula                                                                        ";
		$sql .= " inner join regencia      on regencia.ed59_i_codigo          = regenciasemaula.regencia            ";
		$sql .= " inner join disciplina    on disciplina.ed12_i_codigo        = regencia.ed59_i_disciplina          ";
		$sql .= " inner join caddisciplina on caddisciplina.ed232_i_codigo    = disciplina.ed12_i_caddisciplina     ";


  	$sql2 = "";
  	if ($sWhere != "") {
  		$sql2 = " where $sWhere";
  	}
  	$sql .= $sql2;
  	if($sOrdem != null) {

  		$sql       .= " order by ";
  		$campos_sql = split("#",$sOrdem);
  		$virgula    = "";
  		for ($i = 0; $i < sizeof($campos_sql); $i++) {

  			$sql    .= $virgula.$campos_sql[$i];
  			$virgula = ",";
  		}
  	}
    return $sql;
  }


  function sql_query_diario_classe_periodo_substituicao ($sCampos = "*", $sOrdem = null,
  		                                                   $sWhereRegencia = null , $sWhereSubstituicao) {


  	$sSql  = "  select {$sCampos}                                                       ";
  	$sSql .= "    from regenciahorario                                                  ";
  	$sSql .= "   inner join regencia      on ed58_i_regencia      = ed59_i_codigo       ";
  	$sSql .= "   inner join serie         on ed59_i_serie         = ed11_i_codigo       ";
  	$sSql .= "   inner join turma         on ed59_i_turma         = ed57_i_codigo       ";
  	$sSql .= "   inner join calendario    on  ed57_i_calendario   = ed52_i_codigo       ";
  	$sSql .= "   inner join disciplina    on ed59_i_disciplina    = ed12_i_codigo       ";
  	$sSql .= "   inner join caddisciplina on ed12_i_caddisciplina = ed232_i_codigo      ";
  	$sSql .= "   inner join rechumano     on ed58_i_rechumano     = ed20_i_codigo       ";
  	$sSql .= "   inner join periodoescola on ed58_i_periodo       = ed17_i_codigo       ";
  	$sSql .= "   inner join periodoaula   on ed17_i_periodoaula   = ed08_i_codigo       ";
  	$sSql .= "   where {$sWhereRegencia}                                                ";

  	$sSql .= " union                                                                    ";

  	$sSql .= "  select {$sCampos}                                                       ";
  	$sSql .= "    from docentesubstituto                                                ";
  	$sSql .= "   inner join regenciahorario on ed58_i_regencia      = ed322_regencia    ";
  	$sSql .= "   inner join regencia        on ed58_i_regencia      = ed59_i_codigo     ";
  	$sSql .= "   inner join serie           on ed59_i_serie         = ed11_i_codigo     ";
  	$sSql .= "   inner join turma           on ed59_i_turma         = ed57_i_codigo     ";
  	$sSql .= "   inner join calendario      on  ed57_i_calendario   = ed52_i_codigo     ";
  	$sSql .= "   inner join disciplina      on ed59_i_disciplina    = ed12_i_codigo     ";
  	$sSql .= "   inner join caddisciplina   on ed12_i_caddisciplina = ed232_i_codigo    ";
  	$sSql .= "   inner join rechumano       on ed58_i_rechumano     = ed20_i_codigo     ";
  	$sSql .= "   inner join periodoescola   on ed58_i_periodo       = ed17_i_codigo     ";
  	$sSql .= "   inner join periodoaula     on ed17_i_periodoaula   = ed08_i_codigo     ";
  	$sSql .= "   where {$sWhereSubstituicao}                                            ";

  	return $sSql;
  }

  public function sql_query_diario_classe_periodo_avaliacao($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " from regenciahorario ";
    $sSql .= "      inner join regencia          on ed58_i_regencia      = ed59_i_codigo ";
    $sSql .= "      inner join serie             on ed59_i_serie         = ed11_i_codigo";
    $sSql .= "      inner join turma             on ed59_i_turma         = ed57_i_codigo ";
    $sSql .= "      inner join calendario        on ed57_i_calendario    = ed52_i_codigo ";
    $sSql .= "      inner join periodocalendario on ed53_i_calendario    = ed52_i_codigo ";
    $sSql .= "      inner join disciplina        on ed59_i_disciplina    = ed12_i_codigo ";
    $sSql .= "      inner join caddisciplina     on ed12_i_caddisciplina = ed232_i_codigo ";
    $sSql .= "      inner join rechumano         on ed58_i_rechumano     = ed20_i_codigo ";
    $sSql .= "      inner join periodoescola     on ed58_i_periodo       = ed17_i_codigo";
    $sSql .= "      inner join periodoaula       on ed17_i_periodoaula   = ed08_i_codigo";
    $sSql .= "      left join rechumanocgm       on ed285_i_rechumano    = ed58_i_rechumano ";
    $sSql .= "      left join cgm                on ed285_i_cgm          = cgm.z01_numcgm ";
    $sSql .= "      left join rechumanopessoal   on ed284_i_rechumano    = ed58_i_rechumano ";
    $sSql .= "      left join rhpessoal          on ed284_i_rhpessoal    = rh01_regist ";
    $sSql .= "      left join cgm cgmpessoal     on rh01_numcgm          = cgmpessoal.z01_numcgm ";
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where regenciahorario.ed58_i_codigo = $iCodigo ";
      }
    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }
    }
    return $sSql;
  }
  
  function sql_query_quadro_horario($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {
  
    $sSql = 'select ';
    if ($sCampos != '*') {
  
      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){
  
        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";
  
      }
  
    } else {
      $sSql .= $sCampos;
    }
    $sSql .= ' from regenciahorario ';
    $sSql .= '   inner join periodoescola on periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo';
    $sSql .= '   inner join regencia on regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia';
    $sSql .= '   inner join diasemana on diasemana.ed32_i_codigo = regenciahorario.ed58_i_diasemana';
    $sSql .= '   inner join escola on escola.ed18_i_codigo = periodoescola.ed17_i_escola';
    $sSql .= '   inner join periodoaula on periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula';
    $sSql .= '   inner join turno on turno.ed15_i_codigo = periodoescola.ed17_i_turno';
    $sSql .= '   inner join disciplina on disciplina.ed12_i_codigo = regencia.ed59_i_disciplina';
    $sSql .= '   inner join caddisciplina on caddisciplina.ed232_i_codigo= disciplina.ed12_i_caddisciplina';
    $sSql .= '   inner join turma on turma.ed57_i_codigo = regencia.ed59_i_turma';
    $sSql .= '   inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario';
    $sSql .= '   inner join serie on serie.ed11_i_codigo = regencia.ed59_i_serie';
    $sSql .= '   inner join ensino on ensino.ed10_i_codigo = serie.ed11_i_ensino';
    $sSql .= '   inner join rechumano on rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano';
    $sSql .= '   inner join ((select cgm.*, rechumanopessoal.ed284_i_rechumano as rechumano,';
    $sSql .= '                       1 as tipo ';
    $sSql .= '                  from rechumano as a ';
    $sSql .= '                    inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = a.ed20_i_codigo ';
    $sSql .= '                    inner join rhpessoal on rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal';
    $sSql .= '                    inner join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm';
    $sSql .= '                      where rechumanopessoal.ed284_i_rechumano = a.ed20_i_codigo)';
    $sSql .= '                                      union ';
    $sSql .= '               (select cgm.*, rechumanocgm.ed285_i_rechumano as rechumano,';
    $sSql .= '                       2 as tipo ';
    $sSql .= '                 from rechumano as a ';
    $sSql .= '                   inner join rechumanocgm on rechumanocgm.ed285_i_rechumano = a.ed20_i_codigo ';
    $sSql .= '                   inner join cgm on cgm.z01_numcgm = rechumanocgm.ed285_i_cgm';
    $sSql .= '                     where rechumanocgm.ed285_i_rechumano = a.ed20_i_codigo)) as reccgm';
    $sSql .= '     on reccgm.rechumano = rechumano.ed20_i_codigo ';
    $sSql2 = '';
    if ($sDbWhere == '') {
  
      if ($iCodigo != null ){
        $sSql2 .= " where regenciahorario.ed58_i_codigo = $iCodigo ";
      }
  
    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;
  
    if ($sOrdem != null) {
  
      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {
  
        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';
  
      }
    }
    return $sSql;
  }
}
?>