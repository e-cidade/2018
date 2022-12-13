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
//CLASSE DA ENTIDADE isscadsimplesbaixa
class cl_isscadsimplesbaixa { 
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
   var $q39_sequencial = 0; 
   var $q39_isscadsimples = 0; 
   var $q39_dtbaixa_dia = null; 
   var $q39_dtbaixa_mes = null; 
   var $q39_dtbaixa_ano = null; 
   var $q39_dtbaixa = null; 
   var $q39_issmotivobaixa = 0; 
   var $q39_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q39_sequencial = int4 = Código da Baixa 
                 q39_isscadsimples = int4 = Código do Simples 
                 q39_dtbaixa = date = Data da Baixa 
                 q39_issmotivobaixa = int4 = Motivo da Baixa 
                 q39_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_isscadsimplesbaixa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("isscadsimplesbaixa"); 
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
       $this->q39_sequencial = ($this->q39_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q39_sequencial"]:$this->q39_sequencial);
       $this->q39_isscadsimples = ($this->q39_isscadsimples == ""?@$GLOBALS["HTTP_POST_VARS"]["q39_isscadsimples"]:$this->q39_isscadsimples);
       if($this->q39_dtbaixa == ""){
         $this->q39_dtbaixa_dia = ($this->q39_dtbaixa_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q39_dtbaixa_dia"]:$this->q39_dtbaixa_dia);
         $this->q39_dtbaixa_mes = ($this->q39_dtbaixa_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q39_dtbaixa_mes"]:$this->q39_dtbaixa_mes);
         $this->q39_dtbaixa_ano = ($this->q39_dtbaixa_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q39_dtbaixa_ano"]:$this->q39_dtbaixa_ano);
         if($this->q39_dtbaixa_dia != ""){
            $this->q39_dtbaixa = $this->q39_dtbaixa_ano."-".$this->q39_dtbaixa_mes."-".$this->q39_dtbaixa_dia;
         }
       }
       $this->q39_issmotivobaixa = ($this->q39_issmotivobaixa == ""?@$GLOBALS["HTTP_POST_VARS"]["q39_issmotivobaixa"]:$this->q39_issmotivobaixa);
       $this->q39_obs = ($this->q39_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["q39_obs"]:$this->q39_obs);
     }else{
       $this->q39_sequencial = ($this->q39_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q39_sequencial"]:$this->q39_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q39_sequencial){ 
      $this->atualizacampos();
     if($this->q39_isscadsimples == null ){ 
       $this->erro_sql = " Campo Código do Simples nao Informado.";
       $this->erro_campo = "q39_isscadsimples";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q39_dtbaixa == null ){ 
       $this->erro_sql = " Campo Data da Baixa nao Informado.";
       $this->erro_campo = "q39_dtbaixa_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q39_issmotivobaixa == null ){ 
       $this->erro_sql = " Campo Motivo da Baixa nao Informado.";
       $this->erro_campo = "q39_issmotivobaixa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q39_sequencial == "" || $q39_sequencial == null ){
       $result = db_query("select nextval('isscadsimplebaixa_q39_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: isscadsimplebaixa_q39_sequencial_seq do campo: q39_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q39_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from isscadsimplebaixa_q39_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q39_sequencial)){
         $this->erro_sql = " Campo q39_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q39_sequencial = $q39_sequencial; 
       }
     }
     if(($this->q39_sequencial == null) || ($this->q39_sequencial == "") ){ 
       $this->erro_sql = " Campo q39_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into isscadsimplesbaixa(
                                       q39_sequencial 
                                      ,q39_isscadsimples 
                                      ,q39_dtbaixa 
                                      ,q39_issmotivobaixa 
                                      ,q39_obs 
                       )
                values (
                                $this->q39_sequencial 
                               ,$this->q39_isscadsimples 
                               ,".($this->q39_dtbaixa == "null" || $this->q39_dtbaixa == ""?"null":"'".$this->q39_dtbaixa."'")." 
                               ,$this->q39_issmotivobaixa 
                               ,'$this->q39_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Baixa do cadastro de simples ($this->q39_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Baixa do cadastro de simples já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Baixa do cadastro de simples ($this->q39_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q39_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q39_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10561,'$this->q39_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1822,10561,'','".AddSlashes(pg_result($resaco,0,'q39_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1822,10562,'','".AddSlashes(pg_result($resaco,0,'q39_isscadsimples'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1822,10563,'','".AddSlashes(pg_result($resaco,0,'q39_dtbaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1822,10564,'','".AddSlashes(pg_result($resaco,0,'q39_issmotivobaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1822,10565,'','".AddSlashes(pg_result($resaco,0,'q39_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q39_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update isscadsimplesbaixa set ";
     $virgula = "";
     if(trim($this->q39_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q39_sequencial"])){ 
       $sql  .= $virgula." q39_sequencial = $this->q39_sequencial ";
       $virgula = ",";
       if(trim($this->q39_sequencial) == null ){ 
         $this->erro_sql = " Campo Código da Baixa nao Informado.";
         $this->erro_campo = "q39_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q39_isscadsimples)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q39_isscadsimples"])){ 
       $sql  .= $virgula." q39_isscadsimples = $this->q39_isscadsimples ";
       $virgula = ",";
       if(trim($this->q39_isscadsimples) == null ){ 
         $this->erro_sql = " Campo Código do Simples nao Informado.";
         $this->erro_campo = "q39_isscadsimples";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q39_dtbaixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q39_dtbaixa_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q39_dtbaixa_dia"] !="") ){ 
       $sql  .= $virgula." q39_dtbaixa = '$this->q39_dtbaixa' ";
       $virgula = ",";
       if(trim($this->q39_dtbaixa) == null ){ 
         $this->erro_sql = " Campo Data da Baixa nao Informado.";
         $this->erro_campo = "q39_dtbaixa_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q39_dtbaixa_dia"])){ 
         $sql  .= $virgula." q39_dtbaixa = null ";
         $virgula = ",";
         if(trim($this->q39_dtbaixa) == null ){ 
           $this->erro_sql = " Campo Data da Baixa nao Informado.";
           $this->erro_campo = "q39_dtbaixa_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q39_issmotivobaixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q39_issmotivobaixa"])){ 
       $sql  .= $virgula." q39_issmotivobaixa = $this->q39_issmotivobaixa ";
       $virgula = ",";
       if(trim($this->q39_issmotivobaixa) == null ){ 
         $this->erro_sql = " Campo Motivo da Baixa nao Informado.";
         $this->erro_campo = "q39_issmotivobaixa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q39_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q39_obs"])){ 
       $sql  .= $virgula." q39_obs = '$this->q39_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q39_sequencial!=null){
       $sql .= " q39_sequencial = $this->q39_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q39_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10561,'$this->q39_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q39_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1822,10561,'".AddSlashes(pg_result($resaco,$conresaco,'q39_sequencial'))."','$this->q39_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q39_isscadsimples"]))
           $resac = db_query("insert into db_acount values($acount,1822,10562,'".AddSlashes(pg_result($resaco,$conresaco,'q39_isscadsimples'))."','$this->q39_isscadsimples',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q39_dtbaixa"]))
           $resac = db_query("insert into db_acount values($acount,1822,10563,'".AddSlashes(pg_result($resaco,$conresaco,'q39_dtbaixa'))."','$this->q39_dtbaixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q39_issmotivobaixa"]))
           $resac = db_query("insert into db_acount values($acount,1822,10564,'".AddSlashes(pg_result($resaco,$conresaco,'q39_issmotivobaixa'))."','$this->q39_issmotivobaixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q39_obs"]))
           $resac = db_query("insert into db_acount values($acount,1822,10565,'".AddSlashes(pg_result($resaco,$conresaco,'q39_obs'))."','$this->q39_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Baixa do cadastro de simples nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q39_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Baixa do cadastro de simples nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q39_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q39_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10561,'$q39_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1822,10561,'','".AddSlashes(pg_result($resaco,$iresaco,'q39_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1822,10562,'','".AddSlashes(pg_result($resaco,$iresaco,'q39_isscadsimples'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1822,10563,'','".AddSlashes(pg_result($resaco,$iresaco,'q39_dtbaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1822,10564,'','".AddSlashes(pg_result($resaco,$iresaco,'q39_issmotivobaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1822,10565,'','".AddSlashes(pg_result($resaco,$iresaco,'q39_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from isscadsimplesbaixa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q39_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q39_sequencial = $q39_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Baixa do cadastro de simples nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q39_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Baixa do cadastro de simples nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q39_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:isscadsimplesbaixa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q39_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isscadsimplesbaixa ";
     $sql .= "      inner join isscadsimples  on  isscadsimples.q38_sequencial = isscadsimplesbaixa.q39_isscadsimples";
     $sql .= "      inner join issmotivobaixa  on  issmotivobaixa.q42_sequencial = isscadsimplesbaixa.q39_issmotivobaixa";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = isscadsimples.q38_inscr";
     $sql2 = "";
     if($dbwhere==""){
       if($q39_sequencial!=null ){
         $sql2 .= " where isscadsimplesbaixa.q39_sequencial = $q39_sequencial "; 
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
   function sql_query_cgm ( $q39_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from isscadsimplesbaixa ";
     $sql .= "      inner join isscadsimples  on  isscadsimples.q38_sequencial = isscadsimplesbaixa.q39_isscadsimples";
     $sql .= "      inner join issmotivobaixa  on  issmotivobaixa.q42_sequencial = isscadsimplesbaixa.q39_issmotivobaixa";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = isscadsimples.q38_inscr";
     $sql .= "      inner join cgm on issbase.q02_numcgm = cgm.z01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q39_sequencial!=null ){
         $sql2 .= " where isscadsimplesbaixa.q39_sequencial = $q39_sequencial ";
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
   function sql_query_file ( $q39_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isscadsimplesbaixa ";
     $sql2 = "";
     if($dbwhere==""){
       if($q39_sequencial!=null ){
         $sql2 .= " where isscadsimplesbaixa.q39_sequencial = $q39_sequencial "; 
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