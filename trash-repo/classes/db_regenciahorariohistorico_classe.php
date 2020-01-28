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
//CLASSE DA ENTIDADE regenciahorariohistorico
class cl_regenciahorariohistorico { 
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
   var $ed323_sequencial = 0; 
   var $ed323_docente = 0; 
   var $ed323_substituto = 0; 
   var $ed323_regencia = 0; 
   var $ed323_periodo = 0; 
   var $ed323_diasemana = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed323_sequencial = int4 = Código 
                 ed323_docente = int4 = Docente 
                 ed323_substituto = int4 = Substituto 
                 ed323_regencia = int4 = Regencia 
                 ed323_periodo = int4 = Período 
                 ed323_diasemana = int4 = Dia da semana 
                 ";
   //funcao construtor da classe 
   function cl_regenciahorariohistorico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("regenciahorariohistorico"); 
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
       $this->ed323_sequencial = ($this->ed323_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed323_sequencial"]:$this->ed323_sequencial);
       $this->ed323_docente = ($this->ed323_docente == ""?@$GLOBALS["HTTP_POST_VARS"]["ed323_docente"]:$this->ed323_docente);
       $this->ed323_substituto = ($this->ed323_substituto == ""?@$GLOBALS["HTTP_POST_VARS"]["ed323_substituto"]:$this->ed323_substituto);
       $this->ed323_regencia = ($this->ed323_regencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed323_regencia"]:$this->ed323_regencia);
       $this->ed323_periodo = ($this->ed323_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed323_periodo"]:$this->ed323_periodo);
       $this->ed323_diasemana = ($this->ed323_diasemana == ""?@$GLOBALS["HTTP_POST_VARS"]["ed323_diasemana"]:$this->ed323_diasemana);
     }else{
       $this->ed323_sequencial = ($this->ed323_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed323_sequencial"]:$this->ed323_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed323_sequencial){ 
      $this->atualizacampos();
     if($this->ed323_docente == null ){ 
       $this->erro_sql = " Campo Docente nao Informado.";
       $this->erro_campo = "ed323_docente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed323_substituto == null ){ 
       $this->erro_sql = " Campo Substituto nao Informado.";
       $this->erro_campo = "ed323_substituto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed323_regencia == null ){ 
       $this->erro_sql = " Campo Regencia nao Informado.";
       $this->erro_campo = "ed323_regencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed323_periodo == null ){ 
       $this->erro_sql = " Campo Período nao Informado.";
       $this->erro_campo = "ed323_periodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed323_diasemana == null ){ 
       $this->erro_sql = " Campo Dia da semana nao Informado.";
       $this->erro_campo = "ed323_diasemana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed323_sequencial == "" || $ed323_sequencial == null ){
       $result = db_query("select nextval('regenciahorariohistorico_ed323_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: regenciahorariohistorico_ed323_sequencial_seq do campo: ed323_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed323_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from regenciahorariohistorico_ed323_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed323_sequencial)){
         $this->erro_sql = " Campo ed323_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed323_sequencial = $ed323_sequencial; 
       }
     }
     if(($this->ed323_sequencial == null) || ($this->ed323_sequencial == "") ){ 
       $this->erro_sql = " Campo ed323_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into regenciahorariohistorico(
                                       ed323_sequencial 
                                      ,ed323_docente 
                                      ,ed323_substituto 
                                      ,ed323_regencia 
                                      ,ed323_periodo 
                                      ,ed323_diasemana 
                       )
                values (
                                $this->ed323_sequencial 
                               ,$this->ed323_docente 
                               ,$this->ed323_substituto 
                               ,$this->ed323_regencia 
                               ,$this->ed323_periodo 
                               ,$this->ed323_diasemana 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "historico da regencia horario ($this->ed323_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "historico da regencia horario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "historico da regencia horario ($this->ed323_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed323_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed323_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19768,'$this->ed323_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3543,19768,'','".AddSlashes(pg_result($resaco,0,'ed323_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3543,19769,'','".AddSlashes(pg_result($resaco,0,'ed323_docente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3543,19770,'','".AddSlashes(pg_result($resaco,0,'ed323_substituto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3543,19771,'','".AddSlashes(pg_result($resaco,0,'ed323_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3543,19772,'','".AddSlashes(pg_result($resaco,0,'ed323_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3543,19773,'','".AddSlashes(pg_result($resaco,0,'ed323_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed323_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update regenciahorariohistorico set ";
     $virgula = "";
     if(trim($this->ed323_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed323_sequencial"])){ 
       $sql  .= $virgula." ed323_sequencial = $this->ed323_sequencial ";
       $virgula = ",";
       if(trim($this->ed323_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed323_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed323_docente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed323_docente"])){ 
       $sql  .= $virgula." ed323_docente = $this->ed323_docente ";
       $virgula = ",";
       if(trim($this->ed323_docente) == null ){ 
         $this->erro_sql = " Campo Docente nao Informado.";
         $this->erro_campo = "ed323_docente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed323_substituto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed323_substituto"])){ 
       $sql  .= $virgula." ed323_substituto = $this->ed323_substituto ";
       $virgula = ",";
       if(trim($this->ed323_substituto) == null ){ 
         $this->erro_sql = " Campo Substituto nao Informado.";
         $this->erro_campo = "ed323_substituto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed323_regencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed323_regencia"])){ 
       $sql  .= $virgula." ed323_regencia = $this->ed323_regencia ";
       $virgula = ",";
       if(trim($this->ed323_regencia) == null ){ 
         $this->erro_sql = " Campo Regencia nao Informado.";
         $this->erro_campo = "ed323_regencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed323_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed323_periodo"])){ 
       $sql  .= $virgula." ed323_periodo = $this->ed323_periodo ";
       $virgula = ",";
       if(trim($this->ed323_periodo) == null ){ 
         $this->erro_sql = " Campo Período nao Informado.";
         $this->erro_campo = "ed323_periodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed323_diasemana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed323_diasemana"])){ 
       $sql  .= $virgula." ed323_diasemana = $this->ed323_diasemana ";
       $virgula = ",";
       if(trim($this->ed323_diasemana) == null ){ 
         $this->erro_sql = " Campo Dia da semana nao Informado.";
         $this->erro_campo = "ed323_diasemana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed323_sequencial!=null){
       $sql .= " ed323_sequencial = $this->ed323_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed323_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19768,'$this->ed323_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed323_sequencial"]) || $this->ed323_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3543,19768,'".AddSlashes(pg_result($resaco,$conresaco,'ed323_sequencial'))."','$this->ed323_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed323_docente"]) || $this->ed323_docente != "")
           $resac = db_query("insert into db_acount values($acount,3543,19769,'".AddSlashes(pg_result($resaco,$conresaco,'ed323_docente'))."','$this->ed323_docente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed323_substituto"]) || $this->ed323_substituto != "")
           $resac = db_query("insert into db_acount values($acount,3543,19770,'".AddSlashes(pg_result($resaco,$conresaco,'ed323_substituto'))."','$this->ed323_substituto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed323_regencia"]) || $this->ed323_regencia != "")
           $resac = db_query("insert into db_acount values($acount,3543,19771,'".AddSlashes(pg_result($resaco,$conresaco,'ed323_regencia'))."','$this->ed323_regencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed323_periodo"]) || $this->ed323_periodo != "")
           $resac = db_query("insert into db_acount values($acount,3543,19772,'".AddSlashes(pg_result($resaco,$conresaco,'ed323_periodo'))."','$this->ed323_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed323_diasemana"]) || $this->ed323_diasemana != "")
           $resac = db_query("insert into db_acount values($acount,3543,19773,'".AddSlashes(pg_result($resaco,$conresaco,'ed323_diasemana'))."','$this->ed323_diasemana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "historico da regencia horario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed323_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "historico da regencia horario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed323_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed323_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed323_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed323_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19768,'$ed323_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3543,19768,'','".AddSlashes(pg_result($resaco,$iresaco,'ed323_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3543,19769,'','".AddSlashes(pg_result($resaco,$iresaco,'ed323_docente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3543,19770,'','".AddSlashes(pg_result($resaco,$iresaco,'ed323_substituto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3543,19771,'','".AddSlashes(pg_result($resaco,$iresaco,'ed323_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3543,19772,'','".AddSlashes(pg_result($resaco,$iresaco,'ed323_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3543,19773,'','".AddSlashes(pg_result($resaco,$iresaco,'ed323_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from regenciahorariohistorico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed323_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed323_sequencial = $ed323_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "historico da regencia horario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed323_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "historico da regencia horario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed323_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed323_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:regenciahorariohistorico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed323_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from regenciahorariohistorico ";
     $sql .= "      inner join periodoescola  on  periodoescola.ed17_i_codigo = regenciahorariohistorico.ed323_periodo";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = regenciahorariohistorico.ed323_regencia";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = regenciahorariohistorico.ed323_docente and  rechumano.ed20_i_codigo = regenciahorariohistorico.ed323_substituto";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = regenciahorariohistorico.ed323_diasemana";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = periodoescola.ed17_i_escola";
     $sql .= "      inner join periodoaula  on  periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = periodoescola.ed17_i_turno";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = regencia.ed59_i_serie";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
     $sql .= "      left  join rhregime  on  rhregime.rh30_codreg = rechumano.ed20_i_rhregime";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = rechumano.ed20_i_censoufcert and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufender and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufnat and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = rechumano.ed20_i_censomunicnat and  censomunic. = rechumano.ed20_i_censomunicender";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql .= "      left  join censocartorio  on  censocartorio.ed291_i_codigo = rechumano.ed20_i_censocartorio";
     $sql .= "      left  join rechumano  as a on   a.ed20_i_codigo = rechumano.ed20_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed323_sequencial!=null ){
         $sql2 .= " where regenciahorariohistorico.ed323_sequencial = $ed323_sequencial "; 
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
   function sql_query_file ( $ed323_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from regenciahorariohistorico ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed323_sequencial!=null ){
         $sql2 .= " where regenciahorariohistorico.ed323_sequencial = $ed323_sequencial "; 
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