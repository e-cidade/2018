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

//MODULO: caixa
//CLASSE DA ENTIDADE modcarnepadraocobranca
class cl_modcarnepadraocobranca { 
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
   var $k22_sequencial = 0; 
   var $k22_modcarnepadrao = 0; 
   var $k22_cadban = 0; 
   var $k22_datafim_dia = null; 
   var $k22_datafim_mes = null; 
   var $k22_datafim_ano = null; 
   var $k22_datafim = null; 
   var $k22_dataini_dia = null; 
   var $k22_dataini_mes = null; 
   var $k22_dataini_ano = null; 
   var $k22_dataini = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k22_sequencial = int4 = Sequencial 
                 k22_modcarnepadrao = int4 = Codigo do modelo padrão da instituição 
                 k22_cadban = int4 = Código 
                 k22_datafim = date = Data final 
                 k22_dataini = date = Data inicial 
                 ";
   //funcao construtor da classe 
   function cl_modcarnepadraocobranca() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("modcarnepadraocobranca"); 
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
       $this->k22_sequencial = ($this->k22_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_sequencial"]:$this->k22_sequencial);
       $this->k22_modcarnepadrao = ($this->k22_modcarnepadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_modcarnepadrao"]:$this->k22_modcarnepadrao);
       $this->k22_cadban = ($this->k22_cadban == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_cadban"]:$this->k22_cadban);
       if($this->k22_datafim == ""){
         $this->k22_datafim_dia = ($this->k22_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_datafim_dia"]:$this->k22_datafim_dia);
         $this->k22_datafim_mes = ($this->k22_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_datafim_mes"]:$this->k22_datafim_mes);
         $this->k22_datafim_ano = ($this->k22_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_datafim_ano"]:$this->k22_datafim_ano);
         if($this->k22_datafim_dia != ""){
            $this->k22_datafim = $this->k22_datafim_ano."-".$this->k22_datafim_mes."-".$this->k22_datafim_dia;
         }
       }
       if($this->k22_dataini == ""){
         $this->k22_dataini_dia = ($this->k22_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_dataini_dia"]:$this->k22_dataini_dia);
         $this->k22_dataini_mes = ($this->k22_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_dataini_mes"]:$this->k22_dataini_mes);
         $this->k22_dataini_ano = ($this->k22_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_dataini_ano"]:$this->k22_dataini_ano);
         if($this->k22_dataini_dia != ""){
            $this->k22_dataini = $this->k22_dataini_ano."-".$this->k22_dataini_mes."-".$this->k22_dataini_dia;
         }
       }
     }else{
       $this->k22_sequencial = ($this->k22_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k22_sequencial"]:$this->k22_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k22_sequencial){ 
      $this->atualizacampos();
     if($this->k22_modcarnepadrao == null ){ 
       $this->erro_sql = " Campo Codigo do modelo padrão da instituição nao Informado.";
       $this->erro_campo = "k22_modcarnepadrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_cadban == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "k22_cadban";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_datafim == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "k22_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k22_dataini == null ){ 
       $this->erro_sql = " Campo Data inicial nao Informado.";
       $this->erro_campo = "k22_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k22_sequencial == "" || $k22_sequencial == null ){
       $result = db_query("select nextval('modcarnepadraocobranca_k22_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: modcarnepadraocobranca_k22_sequencial_seq do campo: k22_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k22_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from modcarnepadraocobranca_k22_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k22_sequencial)){
         $this->erro_sql = " Campo k22_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k22_sequencial = $k22_sequencial; 
       }
     }
     if(($this->k22_sequencial == null) || ($this->k22_sequencial == "") ){ 
       $this->erro_sql = " Campo k22_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into modcarnepadraocobranca(
                                       k22_sequencial 
                                      ,k22_modcarnepadrao 
                                      ,k22_cadban 
                                      ,k22_datafim 
                                      ,k22_dataini 
                       )
                values (
                                $this->k22_sequencial 
                               ,$this->k22_modcarnepadrao 
                               ,$this->k22_cadban 
                               ,".($this->k22_datafim == "null" || $this->k22_datafim == ""?"null":"'".$this->k22_datafim."'")." 
                               ,".($this->k22_dataini == "null" || $this->k22_dataini == ""?"null":"'".$this->k22_dataini."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Carnes de cobranca ($this->k22_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Carnes de cobranca já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Carnes de cobranca ($this->k22_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k22_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k22_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9360,'$this->k22_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1609,9360,'','".AddSlashes(pg_result($resaco,0,'k22_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1609,9361,'','".AddSlashes(pg_result($resaco,0,'k22_modcarnepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1609,9362,'','".AddSlashes(pg_result($resaco,0,'k22_cadban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1609,9364,'','".AddSlashes(pg_result($resaco,0,'k22_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1609,9363,'','".AddSlashes(pg_result($resaco,0,'k22_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k22_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update modcarnepadraocobranca set ";
     $virgula = "";
     if(trim($this->k22_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_sequencial"])){ 
       $sql  .= $virgula." k22_sequencial = $this->k22_sequencial ";
       $virgula = ",";
       if(trim($this->k22_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k22_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_modcarnepadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_modcarnepadrao"])){ 
       $sql  .= $virgula." k22_modcarnepadrao = $this->k22_modcarnepadrao ";
       $virgula = ",";
       if(trim($this->k22_modcarnepadrao) == null ){ 
         $this->erro_sql = " Campo Codigo do modelo padrão da instituição nao Informado.";
         $this->erro_campo = "k22_modcarnepadrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_cadban)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_cadban"])){ 
       $sql  .= $virgula." k22_cadban = $this->k22_cadban ";
       $virgula = ",";
       if(trim($this->k22_cadban) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k22_cadban";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k22_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k22_datafim_dia"] !="") ){ 
       $sql  .= $virgula." k22_datafim = '$this->k22_datafim' ";
       $virgula = ",";
       if(trim($this->k22_datafim) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "k22_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k22_datafim_dia"])){ 
         $sql  .= $virgula." k22_datafim = null ";
         $virgula = ",";
         if(trim($this->k22_datafim) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "k22_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k22_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k22_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k22_dataini_dia"] !="") ){ 
       $sql  .= $virgula." k22_dataini = '$this->k22_dataini' ";
       $virgula = ",";
       if(trim($this->k22_dataini) == null ){ 
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "k22_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k22_dataini_dia"])){ 
         $sql  .= $virgula." k22_dataini = null ";
         $virgula = ",";
         if(trim($this->k22_dataini) == null ){ 
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "k22_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($k22_sequencial!=null){
       $sql .= " k22_sequencial = $this->k22_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k22_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9360,'$this->k22_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k22_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1609,9360,'".AddSlashes(pg_result($resaco,$conresaco,'k22_sequencial'))."','$this->k22_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k22_modcarnepadrao"]))
           $resac = db_query("insert into db_acount values($acount,1609,9361,'".AddSlashes(pg_result($resaco,$conresaco,'k22_modcarnepadrao'))."','$this->k22_modcarnepadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k22_cadban"]))
           $resac = db_query("insert into db_acount values($acount,1609,9362,'".AddSlashes(pg_result($resaco,$conresaco,'k22_cadban'))."','$this->k22_cadban',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k22_datafim"]))
           $resac = db_query("insert into db_acount values($acount,1609,9364,'".AddSlashes(pg_result($resaco,$conresaco,'k22_datafim'))."','$this->k22_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k22_dataini"]))
           $resac = db_query("insert into db_acount values($acount,1609,9363,'".AddSlashes(pg_result($resaco,$conresaco,'k22_dataini'))."','$this->k22_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Carnes de cobranca nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k22_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Carnes de cobranca nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k22_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k22_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9360,'$k22_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1609,9360,'','".AddSlashes(pg_result($resaco,$iresaco,'k22_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1609,9361,'','".AddSlashes(pg_result($resaco,$iresaco,'k22_modcarnepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1609,9362,'','".AddSlashes(pg_result($resaco,$iresaco,'k22_cadban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1609,9364,'','".AddSlashes(pg_result($resaco,$iresaco,'k22_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1609,9363,'','".AddSlashes(pg_result($resaco,$iresaco,'k22_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from modcarnepadraocobranca
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k22_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k22_sequencial = $k22_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Carnes de cobranca nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k22_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Carnes de cobranca nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k22_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:modcarnepadraocobranca";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k22_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from modcarnepadraocobranca ";
     $sql .= "      inner join cadban          on  cadban.k15_codigo          = modcarnepadraocobranca.k22_cadban";
     $sql .= "                                and  cadban.k15_instit          = ".db_getsession("DB_instit");
     $sql .= "      inner join modcarnepadrao  on  modcarnepadrao.k48_sequencial = modcarnepadraocobranca.k22_modcarnepadrao";
     $sql .= "      inner join cgm             on  cgm.z01_numcgm             = cadban.k15_numcgm";
     $sql .= "      inner join bancos          on  bancos.codbco              = cadban.k15_codbco";
     $sql .= "      inner join db_config       on  db_config.codigo           = modcarnepadrao.k48_instit";
     $sql .= "      inner join cadtipomod      on  cadtipomod.k46_sequencial  = modcarnepadrao.k48_cadtipomod";
     $sql .= "      inner join cadmodcarne     on  cadmodcarne.k47_sequencial = modcarnepadrao.k48_cadmodcarne";
     $sql2 = "";
     if($dbwhere==""){
       if($k22_sequencial!=null ){
         $sql2 .= " where modcarnepadraocobranca.k22_sequencial = $k22_sequencial "; 
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
   function sql_query_file ( $k22_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from modcarnepadraocobranca ";
     $sql2 = "";
     if($dbwhere==""){
       if($k22_sequencial!=null ){
         $sql2 .= " where modcarnepadraocobranca.k22_sequencial = $k22_sequencial "; 
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