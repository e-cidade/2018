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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE consolidacaodebitosregistros
class cl_consolidacaodebitosregistros { 
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
   var $k162_sequencial = 0; 
   var $k162_receitaorcamento = null; 
   var $k162_receitatesouraria = 0; 
   var $k162_descricao = null; 
   var $k162_valorhistorico = 0; 
   var $k162_valorcorrigido = 0; 
   var $k162_multa = 0; 
   var $k162_juros = 0; 
   var $k162_total = 0; 
   var $k162_valorpagar = 0; 
   var $k162_valorpago = 0; 
   var $k162_descontoconcedido = 0; 
   var $k162_tiporelatorio = 0; 
   var $k162_consolidacaodebitos = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k162_sequencial = int4 = Sequencial 
                 k162_receitaorcamento = varchar(15) = Receita do Orçamento 
                 k162_receitatesouraria = int4 = Receita da Tesouraria 
                 k162_descricao = varchar(255) = Descrição 
                 k162_valorhistorico = float8 = Valor do Histórico 
                 k162_valorcorrigido = float8 = Valor Corrigido 
                 k162_multa = float8 = Multa 
                 k162_juros = float8 = Juros 
                 k162_total = float8 = Total 
                 k162_valorpagar = float8 = Valor a Pagar 
                 k162_valorpago = float8 = Valor Pago 
                 k162_descontoconcedido = float8 = Deconto Concedido 
                 k162_tiporelatorio = int4 = Tipo do Relatório 
                 k162_consolidacaodebitos = int4 = Sequencial Consolidação dos Débitos 
                 ";
   //funcao construtor da classe 
   function cl_consolidacaodebitosregistros() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("consolidacaodebitosregistros"); 
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
       $this->k162_sequencial = ($this->k162_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_sequencial"]:$this->k162_sequencial);
       $this->k162_receitaorcamento = ($this->k162_receitaorcamento == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_receitaorcamento"]:$this->k162_receitaorcamento);
       $this->k162_receitatesouraria = ($this->k162_receitatesouraria == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_receitatesouraria"]:$this->k162_receitatesouraria);
       $this->k162_descricao = ($this->k162_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_descricao"]:$this->k162_descricao);
       $this->k162_valorhistorico = ($this->k162_valorhistorico == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_valorhistorico"]:$this->k162_valorhistorico);
       $this->k162_valorcorrigido = ($this->k162_valorcorrigido == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_valorcorrigido"]:$this->k162_valorcorrigido);
       $this->k162_multa = ($this->k162_multa == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_multa"]:$this->k162_multa);
       $this->k162_juros = ($this->k162_juros == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_juros"]:$this->k162_juros);
       $this->k162_total = ($this->k162_total == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_total"]:$this->k162_total);
       $this->k162_valorpagar = ($this->k162_valorpagar == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_valorpagar"]:$this->k162_valorpagar);
       $this->k162_valorpago = ($this->k162_valorpago == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_valorpago"]:$this->k162_valorpago);
       $this->k162_descontoconcedido = ($this->k162_descontoconcedido == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_descontoconcedido"]:$this->k162_descontoconcedido);
       $this->k162_tiporelatorio = ($this->k162_tiporelatorio == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_tiporelatorio"]:$this->k162_tiporelatorio);
       $this->k162_consolidacaodebitos = ($this->k162_consolidacaodebitos == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_consolidacaodebitos"]:$this->k162_consolidacaodebitos);
     }else{
       $this->k162_sequencial = ($this->k162_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k162_sequencial"]:$this->k162_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k162_sequencial){ 
      $this->atualizacampos();
     if($this->k162_receitaorcamento == null ){ 
       $this->erro_sql = " Campo Receita do Orçamento nao Informado.";
       $this->erro_campo = "k162_receitaorcamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k162_receitatesouraria == null ){ 
       $this->erro_sql = " Campo Receita da Tesouraria nao Informado.";
       $this->erro_campo = "k162_receitatesouraria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k162_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "k162_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k162_valorhistorico == null ){ 
       $this->k162_valorhistorico = "0";
     }
     if($this->k162_valorcorrigido == null ){ 
       $this->k162_valorcorrigido = "0";
     }
     if($this->k162_multa == null ){ 
       $this->k162_multa = "0";
     }
     if($this->k162_juros == null ){ 
       $this->k162_juros = "0";
     }
     if($this->k162_total == null ){ 
       $this->k162_total = "0";
     }
     if($this->k162_valorpagar == null ){ 
       $this->k162_valorpagar = "0";
     }
     if($this->k162_valorpago == null ){ 
       $this->k162_valorpago = "0";
     }
     if($this->k162_descontoconcedido == null ){ 
       $this->k162_descontoconcedido = "0";
     }
     if($this->k162_tiporelatorio == null ){ 
       $this->erro_sql = " Campo Tipo do Relatório nao Informado.";
       $this->erro_campo = "k162_tiporelatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k162_consolidacaodebitos == null ){ 
       $this->erro_sql = " Campo Sequencial Consolidação dos Débitos nao Informado.";
       $this->erro_campo = "k162_consolidacaodebitos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k162_sequencial == "" || $k162_sequencial == null ){
       $result = db_query("select nextval('consolidacaodebitosregistros_k162_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: consolidacaodebitosregistros_k162_sequencial_seq do campo: k162_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k162_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from consolidacaodebitosregistros_k162_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k162_sequencial)){
         $this->erro_sql = " Campo k162_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k162_sequencial = $k162_sequencial; 
       }
     }
     if(($this->k162_sequencial == null) || ($this->k162_sequencial == "") ){ 
       $this->erro_sql = " Campo k162_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into consolidacaodebitosregistros(
                                       k162_sequencial 
                                      ,k162_receitaorcamento 
                                      ,k162_receitatesouraria 
                                      ,k162_descricao 
                                      ,k162_valorhistorico 
                                      ,k162_valorcorrigido 
                                      ,k162_multa 
                                      ,k162_juros 
                                      ,k162_total 
                                      ,k162_valorpagar 
                                      ,k162_valorpago 
                                      ,k162_descontoconcedido 
                                      ,k162_tiporelatorio 
                                      ,k162_consolidacaodebitos 
                       )
                values (
                                $this->k162_sequencial 
                               ,'$this->k162_receitaorcamento' 
                               ,$this->k162_receitatesouraria 
                               ,'$this->k162_descricao' 
                               ,$this->k162_valorhistorico 
                               ,$this->k162_valorcorrigido 
                               ,$this->k162_multa 
                               ,$this->k162_juros 
                               ,$this->k162_total 
                               ,$this->k162_valorpagar 
                               ,$this->k162_valorpago 
                               ,$this->k162_descontoconcedido 
                               ,$this->k162_tiporelatorio 
                               ,$this->k162_consolidacaodebitos 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Consolidação de Débitos Registros ($this->k162_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Consolidação de Débitos Registros já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Consolidação de Débitos Registros ($this->k162_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k162_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k162_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19720,'$this->k162_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3536,19720,'','".AddSlashes(pg_result($resaco,0,'k162_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19721,'','".AddSlashes(pg_result($resaco,0,'k162_receitaorcamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19722,'','".AddSlashes(pg_result($resaco,0,'k162_receitatesouraria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19723,'','".AddSlashes(pg_result($resaco,0,'k162_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19724,'','".AddSlashes(pg_result($resaco,0,'k162_valorhistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19725,'','".AddSlashes(pg_result($resaco,0,'k162_valorcorrigido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19726,'','".AddSlashes(pg_result($resaco,0,'k162_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19727,'','".AddSlashes(pg_result($resaco,0,'k162_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19728,'','".AddSlashes(pg_result($resaco,0,'k162_total'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19729,'','".AddSlashes(pg_result($resaco,0,'k162_valorpagar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19730,'','".AddSlashes(pg_result($resaco,0,'k162_valorpago'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19731,'','".AddSlashes(pg_result($resaco,0,'k162_descontoconcedido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19732,'','".AddSlashes(pg_result($resaco,0,'k162_tiporelatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3536,19733,'','".AddSlashes(pg_result($resaco,0,'k162_consolidacaodebitos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k162_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update consolidacaodebitosregistros set ";
     $virgula = "";
     if(trim($this->k162_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_sequencial"])){ 
       $sql  .= $virgula." k162_sequencial = $this->k162_sequencial ";
       $virgula = ",";
       if(trim($this->k162_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k162_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k162_receitaorcamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_receitaorcamento"])){ 
       $sql  .= $virgula." k162_receitaorcamento = '$this->k162_receitaorcamento' ";
       $virgula = ",";
       if(trim($this->k162_receitaorcamento) == null ){ 
         $this->erro_sql = " Campo Receita do Orçamento nao Informado.";
         $this->erro_campo = "k162_receitaorcamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k162_receitatesouraria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_receitatesouraria"])){ 
       $sql  .= $virgula." k162_receitatesouraria = $this->k162_receitatesouraria ";
       $virgula = ",";
       if(trim($this->k162_receitatesouraria) == null ){ 
         $this->erro_sql = " Campo Receita da Tesouraria nao Informado.";
         $this->erro_campo = "k162_receitatesouraria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k162_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_descricao"])){ 
       $sql  .= $virgula." k162_descricao = '$this->k162_descricao' ";
       $virgula = ",";
       if(trim($this->k162_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "k162_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k162_valorhistorico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_valorhistorico"])){ 
        if(trim($this->k162_valorhistorico)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k162_valorhistorico"])){ 
           $this->k162_valorhistorico = "0" ; 
        } 
       $sql  .= $virgula." k162_valorhistorico = $this->k162_valorhistorico ";
       $virgula = ",";
     }
     if(trim($this->k162_valorcorrigido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_valorcorrigido"])){ 
        if(trim($this->k162_valorcorrigido)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k162_valorcorrigido"])){ 
           $this->k162_valorcorrigido = "0" ; 
        } 
       $sql  .= $virgula." k162_valorcorrigido = $this->k162_valorcorrigido ";
       $virgula = ",";
     }
     if(trim($this->k162_multa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_multa"])){ 
        if(trim($this->k162_multa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k162_multa"])){ 
           $this->k162_multa = "0" ; 
        } 
       $sql  .= $virgula." k162_multa = $this->k162_multa ";
       $virgula = ",";
     }
     if(trim($this->k162_juros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_juros"])){ 
        if(trim($this->k162_juros)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k162_juros"])){ 
           $this->k162_juros = "0" ; 
        } 
       $sql  .= $virgula." k162_juros = $this->k162_juros ";
       $virgula = ",";
     }
     if(trim($this->k162_total)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_total"])){ 
        if(trim($this->k162_total)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k162_total"])){ 
           $this->k162_total = "0" ; 
        } 
       $sql  .= $virgula." k162_total = $this->k162_total ";
       $virgula = ",";
     }
     if(trim($this->k162_valorpagar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_valorpagar"])){ 
        if(trim($this->k162_valorpagar)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k162_valorpagar"])){ 
           $this->k162_valorpagar = "0" ; 
        } 
       $sql  .= $virgula." k162_valorpagar = $this->k162_valorpagar ";
       $virgula = ",";
     }
     if(trim($this->k162_valorpago)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_valorpago"])){ 
        if(trim($this->k162_valorpago)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k162_valorpago"])){ 
           $this->k162_valorpago = "0" ; 
        } 
       $sql  .= $virgula." k162_valorpago = $this->k162_valorpago ";
       $virgula = ",";
     }
     if(trim($this->k162_descontoconcedido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_descontoconcedido"])){ 
        if(trim($this->k162_descontoconcedido)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k162_descontoconcedido"])){ 
           $this->k162_descontoconcedido = "0" ; 
        } 
       $sql  .= $virgula." k162_descontoconcedido = $this->k162_descontoconcedido ";
       $virgula = ",";
     }
     if(trim($this->k162_tiporelatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_tiporelatorio"])){ 
       $sql  .= $virgula." k162_tiporelatorio = $this->k162_tiporelatorio ";
       $virgula = ",";
       if(trim($this->k162_tiporelatorio) == null ){ 
         $this->erro_sql = " Campo Tipo do Relatório nao Informado.";
         $this->erro_campo = "k162_tiporelatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k162_consolidacaodebitos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k162_consolidacaodebitos"])){ 
       $sql  .= $virgula." k162_consolidacaodebitos = $this->k162_consolidacaodebitos ";
       $virgula = ",";
       if(trim($this->k162_consolidacaodebitos) == null ){ 
         $this->erro_sql = " Campo Sequencial Consolidação dos Débitos nao Informado.";
         $this->erro_campo = "k162_consolidacaodebitos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k162_sequencial!=null){
       $sql .= " k162_sequencial = $this->k162_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k162_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19720,'$this->k162_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_sequencial"]) || $this->k162_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3536,19720,'".AddSlashes(pg_result($resaco,$conresaco,'k162_sequencial'))."','$this->k162_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_receitaorcamento"]) || $this->k162_receitaorcamento != "")
           $resac = db_query("insert into db_acount values($acount,3536,19721,'".AddSlashes(pg_result($resaco,$conresaco,'k162_receitaorcamento'))."','$this->k162_receitaorcamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_receitatesouraria"]) || $this->k162_receitatesouraria != "")
           $resac = db_query("insert into db_acount values($acount,3536,19722,'".AddSlashes(pg_result($resaco,$conresaco,'k162_receitatesouraria'))."','$this->k162_receitatesouraria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_descricao"]) || $this->k162_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3536,19723,'".AddSlashes(pg_result($resaco,$conresaco,'k162_descricao'))."','$this->k162_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_valorhistorico"]) || $this->k162_valorhistorico != "")
           $resac = db_query("insert into db_acount values($acount,3536,19724,'".AddSlashes(pg_result($resaco,$conresaco,'k162_valorhistorico'))."','$this->k162_valorhistorico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_valorcorrigido"]) || $this->k162_valorcorrigido != "")
           $resac = db_query("insert into db_acount values($acount,3536,19725,'".AddSlashes(pg_result($resaco,$conresaco,'k162_valorcorrigido'))."','$this->k162_valorcorrigido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_multa"]) || $this->k162_multa != "")
           $resac = db_query("insert into db_acount values($acount,3536,19726,'".AddSlashes(pg_result($resaco,$conresaco,'k162_multa'))."','$this->k162_multa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_juros"]) || $this->k162_juros != "")
           $resac = db_query("insert into db_acount values($acount,3536,19727,'".AddSlashes(pg_result($resaco,$conresaco,'k162_juros'))."','$this->k162_juros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_total"]) || $this->k162_total != "")
           $resac = db_query("insert into db_acount values($acount,3536,19728,'".AddSlashes(pg_result($resaco,$conresaco,'k162_total'))."','$this->k162_total',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_valorpagar"]) || $this->k162_valorpagar != "")
           $resac = db_query("insert into db_acount values($acount,3536,19729,'".AddSlashes(pg_result($resaco,$conresaco,'k162_valorpagar'))."','$this->k162_valorpagar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_valorpago"]) || $this->k162_valorpago != "")
           $resac = db_query("insert into db_acount values($acount,3536,19730,'".AddSlashes(pg_result($resaco,$conresaco,'k162_valorpago'))."','$this->k162_valorpago',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_descontoconcedido"]) || $this->k162_descontoconcedido != "")
           $resac = db_query("insert into db_acount values($acount,3536,19731,'".AddSlashes(pg_result($resaco,$conresaco,'k162_descontoconcedido'))."','$this->k162_descontoconcedido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_tiporelatorio"]) || $this->k162_tiporelatorio != "")
           $resac = db_query("insert into db_acount values($acount,3536,19732,'".AddSlashes(pg_result($resaco,$conresaco,'k162_tiporelatorio'))."','$this->k162_tiporelatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k162_consolidacaodebitos"]) || $this->k162_consolidacaodebitos != "")
           $resac = db_query("insert into db_acount values($acount,3536,19733,'".AddSlashes(pg_result($resaco,$conresaco,'k162_consolidacaodebitos'))."','$this->k162_consolidacaodebitos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Consolidação de Débitos Registros nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k162_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Consolidação de Débitos Registros nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k162_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k162_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k162_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k162_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19720,'$k162_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3536,19720,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19721,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_receitaorcamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19722,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_receitatesouraria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19723,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19724,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_valorhistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19725,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_valorcorrigido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19726,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19727,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19728,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_total'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19729,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_valorpagar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19730,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_valorpago'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19731,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_descontoconcedido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19732,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_tiporelatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3536,19733,'','".AddSlashes(pg_result($resaco,$iresaco,'k162_consolidacaodebitos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from consolidacaodebitosregistros
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k162_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k162_sequencial = $k162_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Consolidação de Débitos Registros nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k162_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Consolidação de Débitos Registros nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k162_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k162_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:consolidacaodebitosregistros";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k162_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from consolidacaodebitosregistros ";
     $sql .= "      inner join consolidacaodebitos  on  consolidacaodebitos.k161_sequencial = consolidacaodebitosregistros.k162_consolidacaodebitos";
     $sql2 = "";
     if($dbwhere==""){
       if($k162_sequencial!=null ){
         $sql2 .= " where consolidacaodebitosregistros.k162_sequencial = $k162_sequencial "; 
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
   function sql_query_file ( $k162_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from consolidacaodebitosregistros ";
     $sql2 = "";
     if($dbwhere==""){
       if($k162_sequencial!=null ){
         $sql2 .= " where consolidacaodebitosregistros.k162_sequencial = $k162_sequencial "; 
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