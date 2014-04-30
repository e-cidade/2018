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

//MODULO: issqn
//CLASSE DA ENTIDADE issnotaavulsacanc
class cl_issnotaavulsacanc { 
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
   var $q63_sequencial = 0; 
   var $q63_usuario = 0; 
   var $q63_issnotaavulsa = 0; 
   var $q63_data_dia = null; 
   var $q63_data_mes = null; 
   var $q63_data_ano = null; 
   var $q63_data = null; 
   var $q63_hora = null; 
   var $q63_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q63_sequencial = int4 = Código Sequencial 
                 q63_usuario = int4 = Código do Usuário 
                 q63_issnotaavulsa = int4 = Código da Nota 
                 q63_data = date = Data do cancelamento 
                 q63_hora = char(5) = Hora d oCancelamento 
                 q63_motivo = text = Motivo do Cancelamento 
                 ";
   //funcao construtor da classe 
   function cl_issnotaavulsacanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issnotaavulsacanc"); 
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
       $this->q63_sequencial = ($this->q63_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q63_sequencial"]:$this->q63_sequencial);
       $this->q63_usuario = ($this->q63_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q63_usuario"]:$this->q63_usuario);
       $this->q63_issnotaavulsa = ($this->q63_issnotaavulsa == ""?@$GLOBALS["HTTP_POST_VARS"]["q63_issnotaavulsa"]:$this->q63_issnotaavulsa);
       if($this->q63_data == ""){
         $this->q63_data_dia = ($this->q63_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q63_data_dia"]:$this->q63_data_dia);
         $this->q63_data_mes = ($this->q63_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q63_data_mes"]:$this->q63_data_mes);
         $this->q63_data_ano = ($this->q63_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q63_data_ano"]:$this->q63_data_ano);
         if($this->q63_data_dia != ""){
            $this->q63_data = $this->q63_data_ano."-".$this->q63_data_mes."-".$this->q63_data_dia;
         }
       }
       $this->q63_hora = ($this->q63_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["q63_hora"]:$this->q63_hora);
       $this->q63_motivo = ($this->q63_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["q63_motivo"]:$this->q63_motivo);
     }else{
       $this->q63_sequencial = ($this->q63_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q63_sequencial"]:$this->q63_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q63_sequencial){ 
      $this->atualizacampos();
     if($this->q63_usuario == null ){ 
       $this->erro_sql = " Campo Código do Usuário nao Informado.";
       $this->erro_campo = "q63_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q63_issnotaavulsa == null ){ 
       $this->erro_sql = " Campo Código da Nota nao Informado.";
       $this->erro_campo = "q63_issnotaavulsa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q63_data == null ){ 
       $this->erro_sql = " Campo Data do cancelamento nao Informado.";
       $this->erro_campo = "q63_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q63_hora == null ){ 
       $this->erro_sql = " Campo Hora d oCancelamento nao Informado.";
       $this->erro_campo = "q63_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q63_sequencial == "" || $q63_sequencial == null ){
       $result = db_query("select nextval('issnotaavulsacaanc_q63_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issnotaavulsacaanc_q63_sequencial_seq do campo: q63_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q63_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issnotaavulsacaanc_q63_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q63_sequencial)){
         $this->erro_sql = " Campo q63_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q63_sequencial = $q63_sequencial; 
       }
     }
     if(($this->q63_sequencial == null) || ($this->q63_sequencial == "") ){ 
       $this->erro_sql = " Campo q63_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issnotaavulsacanc(
                                       q63_sequencial 
                                      ,q63_usuario 
                                      ,q63_issnotaavulsa 
                                      ,q63_data 
                                      ,q63_hora 
                                      ,q63_motivo 
                       )
                values (
                                $this->q63_sequencial 
                               ,$this->q63_usuario 
                               ,$this->q63_issnotaavulsa 
                               ,".($this->q63_data == "null" || $this->q63_data == ""?"null":"'".$this->q63_data."'")." 
                               ,'$this->q63_hora' 
                               ,'$this->q63_motivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cancelamento das notas avulsas ($this->q63_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cancelamento das notas avulsas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cancelamento das notas avulsas ($this->q63_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q63_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q63_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10652,'$this->q63_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1840,10652,'','".AddSlashes(pg_result($resaco,0,'q63_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1840,10653,'','".AddSlashes(pg_result($resaco,0,'q63_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1840,10654,'','".AddSlashes(pg_result($resaco,0,'q63_issnotaavulsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1840,10655,'','".AddSlashes(pg_result($resaco,0,'q63_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1840,10656,'','".AddSlashes(pg_result($resaco,0,'q63_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1840,10657,'','".AddSlashes(pg_result($resaco,0,'q63_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q63_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issnotaavulsacanc set ";
     $virgula = "";
     if(trim($this->q63_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q63_sequencial"])){ 
       $sql  .= $virgula." q63_sequencial = $this->q63_sequencial ";
       $virgula = ",";
       if(trim($this->q63_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "q63_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q63_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q63_usuario"])){ 
       $sql  .= $virgula." q63_usuario = $this->q63_usuario ";
       $virgula = ",";
       if(trim($this->q63_usuario) == null ){ 
         $this->erro_sql = " Campo Código do Usuário nao Informado.";
         $this->erro_campo = "q63_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q63_issnotaavulsa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q63_issnotaavulsa"])){ 
       $sql  .= $virgula." q63_issnotaavulsa = $this->q63_issnotaavulsa ";
       $virgula = ",";
       if(trim($this->q63_issnotaavulsa) == null ){ 
         $this->erro_sql = " Campo Código da Nota nao Informado.";
         $this->erro_campo = "q63_issnotaavulsa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q63_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q63_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q63_data_dia"] !="") ){ 
       $sql  .= $virgula." q63_data = '$this->q63_data' ";
       $virgula = ",";
       if(trim($this->q63_data) == null ){ 
         $this->erro_sql = " Campo Data do cancelamento nao Informado.";
         $this->erro_campo = "q63_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q63_data_dia"])){ 
         $sql  .= $virgula." q63_data = null ";
         $virgula = ",";
         if(trim($this->q63_data) == null ){ 
           $this->erro_sql = " Campo Data do cancelamento nao Informado.";
           $this->erro_campo = "q63_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q63_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q63_hora"])){ 
       $sql  .= $virgula." q63_hora = '$this->q63_hora' ";
       $virgula = ",";
       if(trim($this->q63_hora) == null ){ 
         $this->erro_sql = " Campo Hora d oCancelamento nao Informado.";
         $this->erro_campo = "q63_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q63_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q63_motivo"])){ 
       $sql  .= $virgula." q63_motivo = '$this->q63_motivo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q63_sequencial!=null){
       $sql .= " q63_sequencial = $this->q63_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q63_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10652,'$this->q63_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q63_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1840,10652,'".AddSlashes(pg_result($resaco,$conresaco,'q63_sequencial'))."','$this->q63_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q63_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1840,10653,'".AddSlashes(pg_result($resaco,$conresaco,'q63_usuario'))."','$this->q63_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q63_issnotaavulsa"]))
           $resac = db_query("insert into db_acount values($acount,1840,10654,'".AddSlashes(pg_result($resaco,$conresaco,'q63_issnotaavulsa'))."','$this->q63_issnotaavulsa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q63_data"]))
           $resac = db_query("insert into db_acount values($acount,1840,10655,'".AddSlashes(pg_result($resaco,$conresaco,'q63_data'))."','$this->q63_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q63_hora"]))
           $resac = db_query("insert into db_acount values($acount,1840,10656,'".AddSlashes(pg_result($resaco,$conresaco,'q63_hora'))."','$this->q63_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q63_motivo"]))
           $resac = db_query("insert into db_acount values($acount,1840,10657,'".AddSlashes(pg_result($resaco,$conresaco,'q63_motivo'))."','$this->q63_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento das notas avulsas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q63_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento das notas avulsas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q63_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q63_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10652,'$q63_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1840,10652,'','".AddSlashes(pg_result($resaco,$iresaco,'q63_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1840,10653,'','".AddSlashes(pg_result($resaco,$iresaco,'q63_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1840,10654,'','".AddSlashes(pg_result($resaco,$iresaco,'q63_issnotaavulsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1840,10655,'','".AddSlashes(pg_result($resaco,$iresaco,'q63_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1840,10656,'','".AddSlashes(pg_result($resaco,$iresaco,'q63_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1840,10657,'','".AddSlashes(pg_result($resaco,$iresaco,'q63_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issnotaavulsacanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q63_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q63_sequencial = $q63_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento das notas avulsas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q63_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento das notas avulsas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q63_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issnotaavulsacanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q63_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issnotaavulsacanc ";
     $sql .= "      inner join issnotaavulsa  on  issnotaavulsa.q51_sequencial = issnotaavulsacanc.q63_issnotaavulsa";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issnotaavulsa.q51_inscr";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = issnotaavulsa.q51_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($q63_sequencial!=null ){
         $sql2 .= " where issnotaavulsacanc.q63_sequencial = $q63_sequencial "; 
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
   function sql_query_file ( $q63_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issnotaavulsacanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($q63_sequencial!=null ){
         $sql2 .= " where issnotaavulsacanc.q63_sequencial = $q63_sequencial "; 
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