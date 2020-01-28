<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
  
//MODULO: arrecadacao
//CLASSE DA ENTIDADE declaracaoquitacaocarneagua
class cl_declaracaoquitacaocarneagua { 
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
   var $ar41_sequencial = 0; 
   var $ar41_declaracaoquitacao = 0; 
   var $ar41_numpre = 0; 
   var $ar41_numpar = 0; 
   var $ar41_anoemissao = 0; 
   var $ar41_mesemissao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar41_sequencial = int8 = sequencial 
                 ar41_declaracaoquitacao = int8 = Declaração quitação 
                 ar41_numpre = int4 = Numpre 
                 ar41_numpar = int4 = Parcela 
                 ar41_anoemissao = int4 = Ano Emissão 
                 ar41_mesemissao = char(2) = Mês Emissão 
                 ";
   //funcao construtor da classe 
   function cl_declaracaoquitacaocarneagua() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("declaracaoquitacaocarneagua"); 
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
       $this->ar41_sequencial = ($this->ar41_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar41_sequencial"]:$this->ar41_sequencial);
       $this->ar41_declaracaoquitacao = ($this->ar41_declaracaoquitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar41_declaracaoquitacao"]:$this->ar41_declaracaoquitacao);
       $this->ar41_numpre = ($this->ar41_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["ar41_numpre"]:$this->ar41_numpre);
       $this->ar41_numpar = ($this->ar41_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["ar41_numpar"]:$this->ar41_numpar);
       $this->ar41_anoemissao = ($this->ar41_anoemissao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar41_anoemissao"]:$this->ar41_anoemissao);
       $this->ar41_mesemissao = ($this->ar41_mesemissao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar41_mesemissao"]:$this->ar41_mesemissao);
     }else{
       $this->ar41_sequencial = ($this->ar41_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar41_sequencial"]:$this->ar41_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar41_sequencial){ 
      $this->atualizacampos();
     if($this->ar41_declaracaoquitacao == null ){ 
       $this->erro_sql = " Campo Declaração quitação nao Informado.";
       $this->erro_campo = "ar41_declaracaoquitacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar41_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "ar41_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar41_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "ar41_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar41_anoemissao == null ){ 
       $this->erro_sql = " Campo Ano Emissão nao Informado.";
       $this->erro_campo = "ar41_anoemissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar41_mesemissao == null ){ 
       $this->erro_sql = " Campo Mês Emissão nao Informado.";
       $this->erro_campo = "ar41_mesemissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar41_sequencial == "" || $ar41_sequencial == null ){
       $result = db_query("select nextval('declaracaoquitacaocarneagua_ar41_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: declaracaoquitacaocarneagua_ar41_sequencial_seq do campo: ar41_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar41_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from declaracaoquitacaocarneagua_ar41_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar41_sequencial)){
         $this->erro_sql = " Campo ar41_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar41_sequencial = $ar41_sequencial; 
       }
     }
     if(($this->ar41_sequencial == null) || ($this->ar41_sequencial == "") ){ 
       $this->erro_sql = " Campo ar41_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into declaracaoquitacaocarneagua(
                                       ar41_sequencial 
                                      ,ar41_declaracaoquitacao 
                                      ,ar41_numpre 
                                      ,ar41_numpar 
                                      ,ar41_anoemissao 
                                      ,ar41_mesemissao 
                       )
                values (
                                $this->ar41_sequencial 
                               ,$this->ar41_declaracaoquitacao 
                               ,$this->ar41_numpre 
                               ,$this->ar41_numpar 
                               ,$this->ar41_anoemissao 
                               ,'$this->ar41_mesemissao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->ar41_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->ar41_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar41_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar41_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19292,'$this->ar41_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3431,19292,'','".AddSlashes(pg_result($resaco,0,'ar41_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3431,19293,'','".AddSlashes(pg_result($resaco,0,'ar41_declaracaoquitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3431,19297,'','".AddSlashes(pg_result($resaco,0,'ar41_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3431,19298,'','".AddSlashes(pg_result($resaco,0,'ar41_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3431,19295,'','".AddSlashes(pg_result($resaco,0,'ar41_anoemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3431,19294,'','".AddSlashes(pg_result($resaco,0,'ar41_mesemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar41_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update declaracaoquitacaocarneagua set ";
     $virgula = "";
     if(trim($this->ar41_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar41_sequencial"])){ 
       $sql  .= $virgula." ar41_sequencial = $this->ar41_sequencial ";
       $virgula = ",";
       if(trim($this->ar41_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "ar41_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar41_declaracaoquitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar41_declaracaoquitacao"])){ 
       $sql  .= $virgula." ar41_declaracaoquitacao = $this->ar41_declaracaoquitacao ";
       $virgula = ",";
       if(trim($this->ar41_declaracaoquitacao) == null ){ 
         $this->erro_sql = " Campo Declaração quitação nao Informado.";
         $this->erro_campo = "ar41_declaracaoquitacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar41_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar41_numpre"])){ 
       $sql  .= $virgula." ar41_numpre = $this->ar41_numpre ";
       $virgula = ",";
       if(trim($this->ar41_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "ar41_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar41_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar41_numpar"])){ 
       $sql  .= $virgula." ar41_numpar = $this->ar41_numpar ";
       $virgula = ",";
       if(trim($this->ar41_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "ar41_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar41_anoemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar41_anoemissao"])){ 
       $sql  .= $virgula." ar41_anoemissao = $this->ar41_anoemissao ";
       $virgula = ",";
       if(trim($this->ar41_anoemissao) == null ){ 
         $this->erro_sql = " Campo Ano Emissão nao Informado.";
         $this->erro_campo = "ar41_anoemissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar41_mesemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar41_mesemissao"])){ 
       $sql  .= $virgula." ar41_mesemissao = '$this->ar41_mesemissao' ";
       $virgula = ",";
       if(trim($this->ar41_mesemissao) == null ){ 
         $this->erro_sql = " Campo Mês Emissão nao Informado.";
         $this->erro_campo = "ar41_mesemissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar41_sequencial!=null){
       $sql .= " ar41_sequencial = $this->ar41_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar41_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19292,'$this->ar41_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar41_sequencial"]) || $this->ar41_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3431,19292,'".AddSlashes(pg_result($resaco,$conresaco,'ar41_sequencial'))."','$this->ar41_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar41_declaracaoquitacao"]) || $this->ar41_declaracaoquitacao != "")
           $resac = db_query("insert into db_acount values($acount,3431,19293,'".AddSlashes(pg_result($resaco,$conresaco,'ar41_declaracaoquitacao'))."','$this->ar41_declaracaoquitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar41_numpre"]) || $this->ar41_numpre != "")
           $resac = db_query("insert into db_acount values($acount,3431,19297,'".AddSlashes(pg_result($resaco,$conresaco,'ar41_numpre'))."','$this->ar41_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar41_numpar"]) || $this->ar41_numpar != "")
           $resac = db_query("insert into db_acount values($acount,3431,19298,'".AddSlashes(pg_result($resaco,$conresaco,'ar41_numpar'))."','$this->ar41_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar41_anoemissao"]) || $this->ar41_anoemissao != "")
           $resac = db_query("insert into db_acount values($acount,3431,19295,'".AddSlashes(pg_result($resaco,$conresaco,'ar41_anoemissao'))."','$this->ar41_anoemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar41_mesemissao"]) || $this->ar41_mesemissao != "")
           $resac = db_query("insert into db_acount values($acount,3431,19294,'".AddSlashes(pg_result($resaco,$conresaco,'ar41_mesemissao'))."','$this->ar41_mesemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar41_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar41_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar41_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar41_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar41_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19292,'$ar41_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3431,19292,'','".AddSlashes(pg_result($resaco,$iresaco,'ar41_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3431,19293,'','".AddSlashes(pg_result($resaco,$iresaco,'ar41_declaracaoquitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3431,19297,'','".AddSlashes(pg_result($resaco,$iresaco,'ar41_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3431,19298,'','".AddSlashes(pg_result($resaco,$iresaco,'ar41_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3431,19295,'','".AddSlashes(pg_result($resaco,$iresaco,'ar41_anoemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3431,19294,'','".AddSlashes(pg_result($resaco,$iresaco,'ar41_mesemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from declaracaoquitacaocarneagua
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar41_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar41_sequencial = $ar41_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar41_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar41_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar41_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:declaracaoquitacaocarneagua";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar41_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from declaracaoquitacaocarneagua ";
     $sql .= "      inner join declaracaoquitacao  on  declaracaoquitacao.ar30_sequencial = declaracaoquitacaocarneagua.ar41_declaracaoquitacao";
     $sql .= "      inner join db_config  on  db_config.codigo = declaracaoquitacao.ar30_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = declaracaoquitacao.ar30_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($ar41_sequencial!=null ){
         $sql2 .= " where declaracaoquitacaocarneagua.ar41_sequencial = $ar41_sequencial "; 
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
   function sql_query_file ( $ar41_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from declaracaoquitacaocarneagua ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar41_sequencial!=null ){
         $sql2 .= " where declaracaoquitacaocarneagua.ar41_sequencial = $ar41_sequencial "; 
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
   function sql_declaracao_debito_carne ( $ar33_matric=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from  declaracaoquitacaomatric                                                                                                                               ";
    $sql .= "       inner join declaracaoquitacao           on  declaracaoquitacao.ar30_sequencial                  = declaracaoquitacaomatric.ar33_declaracaoquitacao     ";
    $sql .= "                                              and  declaracaoquitacao.ar30_situacao                    = 1                                                    ";        
    $sql .= "       left  join declaracaoquitacaocarneagua  on  declaracaoquitacaocarneagua.ar41_declaracaoquitacao = declaracaoquitacaomatric.ar33_declaracaoquitacao     ";
    $sql .= " where declaracaoquitacaocarneagua.ar41_declaracaoquitacao                                             is null ";
    $sql2 = "";
  	if($dbwhere==""){
  		if($ar33_matric!=null ){
  	  	$sql2 .= " and declaracaoquitacaomatric.ar33_matric = {$ar33_matric} ";
  		}
  	}else if($dbwhere != ""){
  	 	$sql2 = " and $dbwhere";
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