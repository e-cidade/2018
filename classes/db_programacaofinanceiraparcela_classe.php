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

//MODULO: Caixa
//CLASSE DA ENTIDADE programacaofinanceiraparcela
class cl_programacaofinanceiraparcela { 
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
   var $k118_sequencial = 0; 
   var $k118_programacaofinanceira = 0; 
   var $k118_parcela = 0; 
   var $k118_datapagamento_dia = null; 
   var $k118_datapagamento_mes = null; 
   var $k118_datapagamento_ano = null; 
   var $k118_datapagamento = null; 
   var $k118_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k118_sequencial = int4 = Código Sequencial 
                 k118_programacaofinanceira = int4 = Programacao Financeira 
                 k118_parcela = int4 = Número Parcela 
                 k118_datapagamento = date = Data Pagamento 
                 k118_valor = float8 = Valor a Pagar 
                 ";
   //funcao construtor da classe 
   function cl_programacaofinanceiraparcela() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("programacaofinanceiraparcela"); 
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
       $this->k118_sequencial = ($this->k118_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k118_sequencial"]:$this->k118_sequencial);
       $this->k118_programacaofinanceira = ($this->k118_programacaofinanceira == ""?@$GLOBALS["HTTP_POST_VARS"]["k118_programacaofinanceira"]:$this->k118_programacaofinanceira);
       $this->k118_parcela = ($this->k118_parcela == ""?@$GLOBALS["HTTP_POST_VARS"]["k118_parcela"]:$this->k118_parcela);
       if($this->k118_datapagamento == ""){
         $this->k118_datapagamento_dia = ($this->k118_datapagamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k118_datapagamento_dia"]:$this->k118_datapagamento_dia);
         $this->k118_datapagamento_mes = ($this->k118_datapagamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k118_datapagamento_mes"]:$this->k118_datapagamento_mes);
         $this->k118_datapagamento_ano = ($this->k118_datapagamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k118_datapagamento_ano"]:$this->k118_datapagamento_ano);
         if($this->k118_datapagamento_dia != ""){
            $this->k118_datapagamento = $this->k118_datapagamento_ano."-".$this->k118_datapagamento_mes."-".$this->k118_datapagamento_dia;
         }
       }
       $this->k118_valor = ($this->k118_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k118_valor"]:$this->k118_valor);
     }else{
       $this->k118_sequencial = ($this->k118_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k118_sequencial"]:$this->k118_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k118_sequencial){ 
      $this->atualizacampos();
     if($this->k118_programacaofinanceira == null ){ 
       $this->erro_sql = " Campo Programacao Financeira nao Informado.";
       $this->erro_campo = "k118_programacaofinanceira";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k118_parcela == null ){ 
       $this->erro_sql = " Campo Número Parcela nao Informado.";
       $this->erro_campo = "k118_parcela";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k118_datapagamento == null ){ 
       $this->erro_sql = " Campo Data Pagamento nao Informado.";
       $this->erro_campo = "k118_datapagamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k118_valor == null ){ 
       $this->erro_sql = " Campo Valor a Pagar nao Informado.";
       $this->erro_campo = "k118_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k118_sequencial == "" || $k118_sequencial == null ){
       $result = db_query("select nextval('programacaofinanceiraparcela_k118_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: programacaofinanceiraparcela_k118_sequencial_seq do campo: k118_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k118_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from programacaofinanceiraparcela_k118_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k118_sequencial)){
         $this->erro_sql = " Campo k118_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k118_sequencial = $k118_sequencial; 
       }
     }
     if(($this->k118_sequencial == null) || ($this->k118_sequencial == "") ){ 
       $this->erro_sql = " Campo k118_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into programacaofinanceiraparcela(
                                       k118_sequencial 
                                      ,k118_programacaofinanceira 
                                      ,k118_parcela 
                                      ,k118_datapagamento 
                                      ,k118_valor 
                       )
                values (
                                $this->k118_sequencial 
                               ,$this->k118_programacaofinanceira 
                               ,$this->k118_parcela 
                               ,".($this->k118_datapagamento == "null" || $this->k118_datapagamento == ""?"null":"'".$this->k118_datapagamento."'")." 
                               ,$this->k118_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Programação Financeira Parcela ($this->k118_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Programação Financeira Parcela já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Programação Financeira Parcela ($this->k118_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k118_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k118_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17131,'$this->k118_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3026,17131,'','".AddSlashes(pg_result($resaco,0,'k118_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3026,17132,'','".AddSlashes(pg_result($resaco,0,'k118_programacaofinanceira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3026,17133,'','".AddSlashes(pg_result($resaco,0,'k118_parcela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3026,17134,'','".AddSlashes(pg_result($resaco,0,'k118_datapagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3026,17135,'','".AddSlashes(pg_result($resaco,0,'k118_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k118_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update programacaofinanceiraparcela set ";
     $virgula = "";
     if(trim($this->k118_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k118_sequencial"])){ 
       $sql  .= $virgula." k118_sequencial = $this->k118_sequencial ";
       $virgula = ",";
       if(trim($this->k118_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "k118_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k118_programacaofinanceira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k118_programacaofinanceira"])){ 
       $sql  .= $virgula." k118_programacaofinanceira = $this->k118_programacaofinanceira ";
       $virgula = ",";
       if(trim($this->k118_programacaofinanceira) == null ){ 
         $this->erro_sql = " Campo Programacao Financeira nao Informado.";
         $this->erro_campo = "k118_programacaofinanceira";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k118_parcela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k118_parcela"])){ 
       $sql  .= $virgula." k118_parcela = $this->k118_parcela ";
       $virgula = ",";
       if(trim($this->k118_parcela) == null ){ 
         $this->erro_sql = " Campo Número Parcela nao Informado.";
         $this->erro_campo = "k118_parcela";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k118_datapagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k118_datapagamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k118_datapagamento_dia"] !="") ){ 
       $sql  .= $virgula." k118_datapagamento = '$this->k118_datapagamento' ";
       $virgula = ",";
       if(trim($this->k118_datapagamento) == null ){ 
         $this->erro_sql = " Campo Data Pagamento nao Informado.";
         $this->erro_campo = "k118_datapagamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k118_datapagamento_dia"])){ 
         $sql  .= $virgula." k118_datapagamento = null ";
         $virgula = ",";
         if(trim($this->k118_datapagamento) == null ){ 
           $this->erro_sql = " Campo Data Pagamento nao Informado.";
           $this->erro_campo = "k118_datapagamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k118_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k118_valor"])){ 
       $sql  .= $virgula." k118_valor = $this->k118_valor ";
       $virgula = ",";
       if(trim($this->k118_valor) == null ){ 
         $this->erro_sql = " Campo Valor a Pagar nao Informado.";
         $this->erro_campo = "k118_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k118_sequencial!=null){
       $sql .= " k118_sequencial = $this->k118_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k118_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17131,'$this->k118_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k118_sequencial"]) || $this->k118_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3026,17131,'".AddSlashes(pg_result($resaco,$conresaco,'k118_sequencial'))."','$this->k118_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k118_programacaofinanceira"]) || $this->k118_programacaofinanceira != "")
           $resac = db_query("insert into db_acount values($acount,3026,17132,'".AddSlashes(pg_result($resaco,$conresaco,'k118_programacaofinanceira'))."','$this->k118_programacaofinanceira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k118_parcela"]) || $this->k118_parcela != "")
           $resac = db_query("insert into db_acount values($acount,3026,17133,'".AddSlashes(pg_result($resaco,$conresaco,'k118_parcela'))."','$this->k118_parcela',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k118_datapagamento"]) || $this->k118_datapagamento != "")
           $resac = db_query("insert into db_acount values($acount,3026,17134,'".AddSlashes(pg_result($resaco,$conresaco,'k118_datapagamento'))."','$this->k118_datapagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k118_valor"]) || $this->k118_valor != "")
           $resac = db_query("insert into db_acount values($acount,3026,17135,'".AddSlashes(pg_result($resaco,$conresaco,'k118_valor'))."','$this->k118_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programação Financeira Parcela nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k118_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Programação Financeira Parcela nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k118_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k118_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k118_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k118_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17131,'$k118_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3026,17131,'','".AddSlashes(pg_result($resaco,$iresaco,'k118_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3026,17132,'','".AddSlashes(pg_result($resaco,$iresaco,'k118_programacaofinanceira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3026,17133,'','".AddSlashes(pg_result($resaco,$iresaco,'k118_parcela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3026,17134,'','".AddSlashes(pg_result($resaco,$iresaco,'k118_datapagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3026,17135,'','".AddSlashes(pg_result($resaco,$iresaco,'k118_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from programacaofinanceiraparcela
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k118_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k118_sequencial = $k118_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programação Financeira Parcela nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k118_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Programação Financeira Parcela nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k118_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k118_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:programacaofinanceiraparcela";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k118_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from programacaofinanceiraparcela ";
     $sql .= "      inner join programacaofinanceira  on  programacaofinanceira.k117_sequencial = programacaofinanceiraparcela.k118_programacaofinanceira";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = programacaofinanceira.k117_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($k118_sequencial!=null ){
         $sql2 .= " where programacaofinanceiraparcela.k118_sequencial = $k118_sequencial "; 
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
   function sql_query_file ( $k118_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from programacaofinanceiraparcela ";
     $sql2 = "";
     if($dbwhere==""){
       if($k118_sequencial!=null ){
         $sql2 .= " where programacaofinanceiraparcela.k118_sequencial = $k118_sequencial "; 
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