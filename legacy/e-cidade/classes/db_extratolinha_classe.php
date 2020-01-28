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

//MODULO: caixa
//CLASSE DA ENTIDADE extratolinha
class cl_extratolinha { 
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
   var $k86_sequencial = 0; 
   var $k86_extrato = 0; 
   var $k86_bancohistmov = 0; 
   var $k86_contabancaria = 0; 
   var $k86_data_dia = null; 
   var $k86_data_mes = null; 
   var $k86_data_ano = null; 
   var $k86_data = null; 
   var $k86_valor = 0; 
   var $k86_tipo = null; 
   var $k86_historico = null; 
   var $k86_documento = null; 
   var $k86_lote = null; 
   var $k86_loteseq = null; 
   var $k86_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k86_sequencial = int8 = Codigo sequencial 
                 k86_extrato = int4 = Codigo do extrato 
                 k86_bancohistmov = int4 = Codigo do movimento no banco 
                 k86_contabancaria = int4 = Codigo sequencial da conta bancaria 
                 k86_data = date = Data 
                 k86_valor = float8 = Valor 
                 k86_tipo = char(1) = Tipo 
                 k86_historico = varchar(50) = Historico 
                 k86_documento = varchar(20) = Documento 
                 k86_lote = char(4) = Lote 
                 k86_loteseq = char(5) = Sequencial do lote 
                 k86_observacao = text = Observação referente ao extrato 
                 ";
   //funcao construtor da classe 
   function cl_extratolinha() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("extratolinha"); 
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
       $this->k86_sequencial = ($this->k86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_sequencial"]:$this->k86_sequencial);
       $this->k86_extrato = ($this->k86_extrato == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_extrato"]:$this->k86_extrato);
       $this->k86_bancohistmov = ($this->k86_bancohistmov == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_bancohistmov"]:$this->k86_bancohistmov);
       $this->k86_contabancaria = ($this->k86_contabancaria == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_contabancaria"]:$this->k86_contabancaria);
       if($this->k86_data == ""){
         $this->k86_data_dia = ($this->k86_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_data_dia"]:$this->k86_data_dia);
         $this->k86_data_mes = ($this->k86_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_data_mes"]:$this->k86_data_mes);
         $this->k86_data_ano = ($this->k86_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_data_ano"]:$this->k86_data_ano);
         if($this->k86_data_dia != ""){
            $this->k86_data = $this->k86_data_ano."-".$this->k86_data_mes."-".$this->k86_data_dia;
         }
       }
       $this->k86_valor = ($this->k86_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_valor"]:$this->k86_valor);
       $this->k86_tipo = ($this->k86_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_tipo"]:$this->k86_tipo);
       $this->k86_historico = ($this->k86_historico == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_historico"]:$this->k86_historico);
       $this->k86_documento = ($this->k86_documento == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_documento"]:$this->k86_documento);
       $this->k86_lote = ($this->k86_lote == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_lote"]:$this->k86_lote);
       $this->k86_loteseq = ($this->k86_loteseq == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_loteseq"]:$this->k86_loteseq);
       $this->k86_observacao = ($this->k86_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_observacao"]:$this->k86_observacao);
     }else{
       $this->k86_sequencial = ($this->k86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k86_sequencial"]:$this->k86_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k86_sequencial){ 
      $this->atualizacampos();
     if($this->k86_extrato == null ){ 
       $this->erro_sql = " Campo Codigo do extrato nao Informado.";
       $this->erro_campo = "k86_extrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k86_bancohistmov == null ){ 
       $this->erro_sql = " Campo Codigo do movimento no banco nao Informado.";
       $this->erro_campo = "k86_bancohistmov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k86_contabancaria == null ){ 
       $this->erro_sql = " Campo Codigo sequencial da conta bancaria nao Informado.";
       $this->erro_campo = "k86_contabancaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k86_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k86_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k86_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "k86_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k86_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "k86_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k86_historico == null ){ 
       $this->erro_sql = " Campo Historico nao Informado.";
       $this->erro_campo = "k86_historico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k86_documento == null ){ 
       $this->erro_sql = " Campo Documento nao Informado.";
       $this->erro_campo = "k86_documento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k86_lote == null ){ 
       $this->erro_sql = " Campo Lote nao Informado.";
       $this->erro_campo = "k86_lote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k86_loteseq == null ){ 
       $this->erro_sql = " Campo Sequencial do lote nao Informado.";
       $this->erro_campo = "k86_loteseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k86_sequencial == "" || $k86_sequencial == null ){
       $result = db_query("select nextval('extratolinha_k86_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: extratolinha_k86_sequencial_seq do campo: k86_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k86_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from extratolinha_k86_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k86_sequencial)){
         $this->erro_sql = " Campo k86_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k86_sequencial = $k86_sequencial; 
       }
     }
     if(($this->k86_sequencial == null) || ($this->k86_sequencial == "") ){ 
       $this->erro_sql = " Campo k86_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into extratolinha(
                                       k86_sequencial 
                                      ,k86_extrato 
                                      ,k86_bancohistmov 
                                      ,k86_contabancaria 
                                      ,k86_data 
                                      ,k86_valor 
                                      ,k86_tipo 
                                      ,k86_historico 
                                      ,k86_documento 
                                      ,k86_lote 
                                      ,k86_loteseq 
                                      ,k86_observacao 
                       )
                values (
                                $this->k86_sequencial 
                               ,$this->k86_extrato 
                               ,$this->k86_bancohistmov 
                               ,$this->k86_contabancaria 
                               ,".($this->k86_data == "null" || $this->k86_data == ""?"null":"'".$this->k86_data."'")." 
                               ,$this->k86_valor 
                               ,'$this->k86_tipo' 
                               ,'$this->k86_historico' 
                               ,'$this->k86_documento' 
                               ,'$this->k86_lote' 
                               ,'$this->k86_loteseq' 
                               ,'$this->k86_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Linhas do extrato ($this->k86_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Linhas do extrato já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Linhas do extrato ($this->k86_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k86_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k86_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10041,'$this->k86_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1732,10041,'','".AddSlashes(pg_result($resaco,0,'k86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1732,10075,'','".AddSlashes(pg_result($resaco,0,'k86_extrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1732,10074,'','".AddSlashes(pg_result($resaco,0,'k86_bancohistmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1732,15632,'','".AddSlashes(pg_result($resaco,0,'k86_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1732,10076,'','".AddSlashes(pg_result($resaco,0,'k86_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1732,10077,'','".AddSlashes(pg_result($resaco,0,'k86_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1732,10078,'','".AddSlashes(pg_result($resaco,0,'k86_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1732,10079,'','".AddSlashes(pg_result($resaco,0,'k86_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1732,10080,'','".AddSlashes(pg_result($resaco,0,'k86_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1732,10081,'','".AddSlashes(pg_result($resaco,0,'k86_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1732,10082,'','".AddSlashes(pg_result($resaco,0,'k86_loteseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1732,19288,'','".AddSlashes(pg_result($resaco,0,'k86_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k86_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update extratolinha set ";
     $virgula = "";
     if(trim($this->k86_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k86_sequencial"])){ 
       $sql  .= $virgula." k86_sequencial = $this->k86_sequencial ";
       $virgula = ",";
       if(trim($this->k86_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "k86_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k86_extrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k86_extrato"])){ 
       $sql  .= $virgula." k86_extrato = $this->k86_extrato ";
       $virgula = ",";
       if(trim($this->k86_extrato) == null ){ 
         $this->erro_sql = " Campo Codigo do extrato nao Informado.";
         $this->erro_campo = "k86_extrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k86_bancohistmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k86_bancohistmov"])){ 
       $sql  .= $virgula." k86_bancohistmov = $this->k86_bancohistmov ";
       $virgula = ",";
       if(trim($this->k86_bancohistmov) == null ){ 
         $this->erro_sql = " Campo Codigo do movimento no banco nao Informado.";
         $this->erro_campo = "k86_bancohistmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k86_contabancaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k86_contabancaria"])){ 
       $sql  .= $virgula." k86_contabancaria = $this->k86_contabancaria ";
       $virgula = ",";
       if(trim($this->k86_contabancaria) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial da conta bancaria nao Informado.";
         $this->erro_campo = "k86_contabancaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k86_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k86_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k86_data_dia"] !="") ){ 
       $sql  .= $virgula." k86_data = '$this->k86_data' ";
       $virgula = ",";
       if(trim($this->k86_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k86_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k86_data_dia"])){ 
         $sql  .= $virgula." k86_data = null ";
         $virgula = ",";
         if(trim($this->k86_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k86_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k86_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k86_valor"])){ 
       $sql  .= $virgula." k86_valor = $this->k86_valor ";
       $virgula = ",";
       if(trim($this->k86_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "k86_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k86_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k86_tipo"])){ 
       $sql  .= $virgula." k86_tipo = '$this->k86_tipo' ";
       $virgula = ",";
       if(trim($this->k86_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "k86_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k86_historico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k86_historico"])){ 
       $sql  .= $virgula." k86_historico = '$this->k86_historico' ";
       $virgula = ",";
       if(trim($this->k86_historico) == null ){ 
         $this->erro_sql = " Campo Historico nao Informado.";
         $this->erro_campo = "k86_historico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k86_documento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k86_documento"])){ 
       $sql  .= $virgula." k86_documento = '$this->k86_documento' ";
       $virgula = ",";
       if(trim($this->k86_documento) == null ){ 
         $this->erro_sql = " Campo Documento nao Informado.";
         $this->erro_campo = "k86_documento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k86_lote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k86_lote"])){ 
       $sql  .= $virgula." k86_lote = '$this->k86_lote' ";
       $virgula = ",";
       if(trim($this->k86_lote) == null ){ 
         $this->erro_sql = " Campo Lote nao Informado.";
         $this->erro_campo = "k86_lote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k86_loteseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k86_loteseq"])){ 
       $sql  .= $virgula." k86_loteseq = '$this->k86_loteseq' ";
       $virgula = ",";
       if(trim($this->k86_loteseq) == null ){ 
         $this->erro_sql = " Campo Sequencial do lote nao Informado.";
         $this->erro_campo = "k86_loteseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k86_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k86_observacao"])){ 
       $sql  .= $virgula." k86_observacao = '$this->k86_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k86_sequencial!=null){
       $sql .= " k86_sequencial = $this->k86_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k86_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10041,'$this->k86_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k86_sequencial"]) || $this->k86_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,1732,10041,'".AddSlashes(pg_result($resaco,$conresaco,'k86_sequencial'))."','$this->k86_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k86_extrato"]) || $this->k86_extrato != "")
           $resac = db_query("insert into db_acount values($acount,1732,10075,'".AddSlashes(pg_result($resaco,$conresaco,'k86_extrato'))."','$this->k86_extrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k86_bancohistmov"]) || $this->k86_bancohistmov != "")
           $resac = db_query("insert into db_acount values($acount,1732,10074,'".AddSlashes(pg_result($resaco,$conresaco,'k86_bancohistmov'))."','$this->k86_bancohistmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k86_contabancaria"]) || $this->k86_contabancaria != "")
           $resac = db_query("insert into db_acount values($acount,1732,15632,'".AddSlashes(pg_result($resaco,$conresaco,'k86_contabancaria'))."','$this->k86_contabancaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k86_data"]) || $this->k86_data != "")
           $resac = db_query("insert into db_acount values($acount,1732,10076,'".AddSlashes(pg_result($resaco,$conresaco,'k86_data'))."','$this->k86_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k86_valor"]) || $this->k86_valor != "")
           $resac = db_query("insert into db_acount values($acount,1732,10077,'".AddSlashes(pg_result($resaco,$conresaco,'k86_valor'))."','$this->k86_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k86_tipo"]) || $this->k86_tipo != "")
           $resac = db_query("insert into db_acount values($acount,1732,10078,'".AddSlashes(pg_result($resaco,$conresaco,'k86_tipo'))."','$this->k86_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k86_historico"]) || $this->k86_historico != "")
           $resac = db_query("insert into db_acount values($acount,1732,10079,'".AddSlashes(pg_result($resaco,$conresaco,'k86_historico'))."','$this->k86_historico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k86_documento"]) || $this->k86_documento != "")
           $resac = db_query("insert into db_acount values($acount,1732,10080,'".AddSlashes(pg_result($resaco,$conresaco,'k86_documento'))."','$this->k86_documento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k86_lote"]) || $this->k86_lote != "")
           $resac = db_query("insert into db_acount values($acount,1732,10081,'".AddSlashes(pg_result($resaco,$conresaco,'k86_lote'))."','$this->k86_lote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k86_loteseq"]) || $this->k86_loteseq != "")
           $resac = db_query("insert into db_acount values($acount,1732,10082,'".AddSlashes(pg_result($resaco,$conresaco,'k86_loteseq'))."','$this->k86_loteseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k86_observacao"]) || $this->k86_observacao != "")
           $resac = db_query("insert into db_acount values($acount,1732,19288,'".AddSlashes(pg_result($resaco,$conresaco,'k86_observacao'))."','$this->k86_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Linhas do extrato nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k86_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Linhas do extrato nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k86_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k86_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10041,'$k86_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1732,10041,'','".AddSlashes(pg_result($resaco,$iresaco,'k86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1732,10075,'','".AddSlashes(pg_result($resaco,$iresaco,'k86_extrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1732,10074,'','".AddSlashes(pg_result($resaco,$iresaco,'k86_bancohistmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1732,15632,'','".AddSlashes(pg_result($resaco,$iresaco,'k86_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1732,10076,'','".AddSlashes(pg_result($resaco,$iresaco,'k86_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1732,10077,'','".AddSlashes(pg_result($resaco,$iresaco,'k86_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1732,10078,'','".AddSlashes(pg_result($resaco,$iresaco,'k86_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1732,10079,'','".AddSlashes(pg_result($resaco,$iresaco,'k86_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1732,10080,'','".AddSlashes(pg_result($resaco,$iresaco,'k86_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1732,10081,'','".AddSlashes(pg_result($resaco,$iresaco,'k86_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1732,10082,'','".AddSlashes(pg_result($resaco,$iresaco,'k86_loteseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1732,19288,'','".AddSlashes(pg_result($resaco,$iresaco,'k86_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from extratolinha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k86_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k86_sequencial = $k86_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Linhas do extrato nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k86_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Linhas do extrato nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k86_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:extratolinha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from extratolinha ";
     $sql .= "      inner join bancoshistmov  on  bancoshistmov.k66_sequencial = extratolinha.k86_bancohistmov";
     $sql .= "      inner join extrato  on  extrato.k85_sequencial = extratolinha.k86_extrato";
     $sql .= "      inner join contabancaria  on  contabancaria.db83_sequencial = extratolinha.k86_contabancaria";
     $sql .= "      inner join bancos  on  bancos.codbco = bancoshistmov.k66_codbco";
     $sql .= "      inner join bancoshistmovcategoria  on  bancoshistmovcategoria.k67_sequencial = bancoshistmov.k66_bancoshistmovcategoria";
     $sql .= "      inner join bancos as b2            on  b2.codbco						 = extrato.k85_codbco";
     $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia";
     $sql2 = "";
     if($dbwhere==""){
       if($k86_sequencial!=null ){
         $sql2 .= " where extratolinha.k86_sequencial = $k86_sequencial "; 
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
   function sql_query_file ( $k86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from extratolinha ";
     $sql2 = "";
     if($dbwhere==""){
       if($k86_sequencial!=null ){
         $sql2 .= " where extratolinha.k86_sequencial = $k86_sequencial "; 
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