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

//MODULO: Custos
//CLASSE DA ENTIDADE custoplanilhaapuracao
class cl_custoplanilhaapuracao { 
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
   var $cc17_sequencial = 0; 
   var $cc17_custoplanilhaorigem = 0; 
   var $cc17_custoplanilha = 0; 
   var $cc17_custoplanoanalitica = 0; 
   var $cc17_quantidade = 0; 
   var $cc17_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc17_sequencial = int4 = Sequencial 
                 cc17_custoplanilhaorigem = int4 = Custo Planilha Origem 
                 cc17_custoplanilha = int4 = Custo Planilha 
                 cc17_custoplanoanalitica = int4 = Custo Plano Analítica 
                 cc17_quantidade = float4 = Quantidade 
                 cc17_valor = float4 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_custoplanilhaapuracao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("custoplanilhaapuracao"); 
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
       $this->cc17_sequencial = ($this->cc17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc17_sequencial"]:$this->cc17_sequencial);
       $this->cc17_custoplanilhaorigem = ($this->cc17_custoplanilhaorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["cc17_custoplanilhaorigem"]:$this->cc17_custoplanilhaorigem);
       $this->cc17_custoplanilha = ($this->cc17_custoplanilha == ""?@$GLOBALS["HTTP_POST_VARS"]["cc17_custoplanilha"]:$this->cc17_custoplanilha);
       $this->cc17_custoplanoanalitica = ($this->cc17_custoplanoanalitica == ""?@$GLOBALS["HTTP_POST_VARS"]["cc17_custoplanoanalitica"]:$this->cc17_custoplanoanalitica);
       $this->cc17_quantidade = ($this->cc17_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["cc17_quantidade"]:$this->cc17_quantidade);
       $this->cc17_valor = ($this->cc17_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["cc17_valor"]:$this->cc17_valor);
     }else{
       $this->cc17_sequencial = ($this->cc17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc17_sequencial"]:$this->cc17_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cc17_sequencial){ 
      $this->atualizacampos();
     if($this->cc17_custoplanilhaorigem == null ){ 
       $this->erro_sql = " Campo Custo Planilha Origem nao Informado.";
       $this->erro_campo = "cc17_custoplanilhaorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc17_custoplanilha == null ){ 
       $this->erro_sql = " Campo Custo Planilha nao Informado.";
       $this->erro_campo = "cc17_custoplanilha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc17_custoplanoanalitica == null ){ 
       $this->erro_sql = " Campo Custo Plano Analítica nao Informado.";
       $this->erro_campo = "cc17_custoplanoanalitica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc17_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "cc17_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc17_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "cc17_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc17_sequencial == "" || $cc17_sequencial == null ){
       $result = db_query("select nextval('custoplanilhaapuracao_cc17_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: custoplanilhaapuracao_cc17_sequencial_seq do campo: cc17_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc17_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from custoplanilhaapuracao_cc17_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc17_sequencial)){
         $this->erro_sql = " Campo cc17_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc17_sequencial = $cc17_sequencial; 
       }
     }
     if(($this->cc17_sequencial == null) || ($this->cc17_sequencial == "") ){ 
       $this->erro_sql = " Campo cc17_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into custoplanilhaapuracao(
                                       cc17_sequencial 
                                      ,cc17_custoplanilhaorigem 
                                      ,cc17_custoplanilha 
                                      ,cc17_custoplanoanalitica 
                                      ,cc17_quantidade 
                                      ,cc17_valor 
                       )
                values (
                                $this->cc17_sequencial 
                               ,$this->cc17_custoplanilhaorigem 
                               ,$this->cc17_custoplanilha 
                               ,$this->cc17_custoplanoanalitica 
                               ,$this->cc17_quantidade 
                               ,$this->cc17_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Custo Planilha Apuração ($this->cc17_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Custo Planilha Apuração já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Custo Planilha Apuração ($this->cc17_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc17_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cc17_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15126,'$this->cc17_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2662,15126,'','".AddSlashes(pg_result($resaco,0,'cc17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2662,15127,'','".AddSlashes(pg_result($resaco,0,'cc17_custoplanilhaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2662,15128,'','".AddSlashes(pg_result($resaco,0,'cc17_custoplanilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2662,15129,'','".AddSlashes(pg_result($resaco,0,'cc17_custoplanoanalitica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2662,15130,'','".AddSlashes(pg_result($resaco,0,'cc17_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2662,15131,'','".AddSlashes(pg_result($resaco,0,'cc17_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cc17_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update custoplanilhaapuracao set ";
     $virgula = "";
     if(trim($this->cc17_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc17_sequencial"])){ 
       $sql  .= $virgula." cc17_sequencial = $this->cc17_sequencial ";
       $virgula = ",";
       if(trim($this->cc17_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "cc17_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc17_custoplanilhaorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc17_custoplanilhaorigem"])){ 
       $sql  .= $virgula." cc17_custoplanilhaorigem = $this->cc17_custoplanilhaorigem ";
       $virgula = ",";
       if(trim($this->cc17_custoplanilhaorigem) == null ){ 
         $this->erro_sql = " Campo Custo Planilha Origem nao Informado.";
         $this->erro_campo = "cc17_custoplanilhaorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc17_custoplanilha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc17_custoplanilha"])){ 
       $sql  .= $virgula." cc17_custoplanilha = $this->cc17_custoplanilha ";
       $virgula = ",";
       if(trim($this->cc17_custoplanilha) == null ){ 
         $this->erro_sql = " Campo Custo Planilha nao Informado.";
         $this->erro_campo = "cc17_custoplanilha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc17_custoplanoanalitica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc17_custoplanoanalitica"])){ 
       $sql  .= $virgula." cc17_custoplanoanalitica = $this->cc17_custoplanoanalitica ";
       $virgula = ",";
       if(trim($this->cc17_custoplanoanalitica) == null ){ 
         $this->erro_sql = " Campo Custo Plano Analítica nao Informado.";
         $this->erro_campo = "cc17_custoplanoanalitica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc17_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc17_quantidade"])){ 
       $sql  .= $virgula." cc17_quantidade = $this->cc17_quantidade ";
       $virgula = ",";
       if(trim($this->cc17_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "cc17_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc17_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc17_valor"])){ 
       $sql  .= $virgula." cc17_valor = $this->cc17_valor ";
       $virgula = ",";
       if(trim($this->cc17_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "cc17_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc17_sequencial!=null){
       $sql .= " cc17_sequencial = $this->cc17_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cc17_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15126,'$this->cc17_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc17_sequencial"]) || $this->cc17_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2662,15126,'".AddSlashes(pg_result($resaco,$conresaco,'cc17_sequencial'))."','$this->cc17_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc17_custoplanilhaorigem"]) || $this->cc17_custoplanilhaorigem != "")
           $resac = db_query("insert into db_acount values($acount,2662,15127,'".AddSlashes(pg_result($resaco,$conresaco,'cc17_custoplanilhaorigem'))."','$this->cc17_custoplanilhaorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc17_custoplanilha"]) || $this->cc17_custoplanilha != "")
           $resac = db_query("insert into db_acount values($acount,2662,15128,'".AddSlashes(pg_result($resaco,$conresaco,'cc17_custoplanilha'))."','$this->cc17_custoplanilha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc17_custoplanoanalitica"]) || $this->cc17_custoplanoanalitica != "")
           $resac = db_query("insert into db_acount values($acount,2662,15129,'".AddSlashes(pg_result($resaco,$conresaco,'cc17_custoplanoanalitica'))."','$this->cc17_custoplanoanalitica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc17_quantidade"]) || $this->cc17_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,2662,15130,'".AddSlashes(pg_result($resaco,$conresaco,'cc17_quantidade'))."','$this->cc17_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc17_valor"]) || $this->cc17_valor != "")
           $resac = db_query("insert into db_acount values($acount,2662,15131,'".AddSlashes(pg_result($resaco,$conresaco,'cc17_valor'))."','$this->cc17_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo Planilha Apuração nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo Planilha Apuração nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cc17_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cc17_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15126,'$cc17_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2662,15126,'','".AddSlashes(pg_result($resaco,$iresaco,'cc17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2662,15127,'','".AddSlashes(pg_result($resaco,$iresaco,'cc17_custoplanilhaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2662,15128,'','".AddSlashes(pg_result($resaco,$iresaco,'cc17_custoplanilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2662,15129,'','".AddSlashes(pg_result($resaco,$iresaco,'cc17_custoplanoanalitica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2662,15130,'','".AddSlashes(pg_result($resaco,$iresaco,'cc17_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2662,15131,'','".AddSlashes(pg_result($resaco,$iresaco,'cc17_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from custoplanilhaapuracao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cc17_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cc17_sequencial = $cc17_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo Planilha Apuração nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo Planilha Apuração nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc17_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:custoplanilhaapuracao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cc17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplanilhaapuracao ";
     $sql .= "      inner join custoplanoanalitica  on  custoplanoanalitica.cc04_sequencial = custoplanilhaapuracao.cc17_custoplanoanalitica";
     $sql .= "      inner join custoplanilhaorigem  on  custoplanilhaorigem.cc14_sequencial = custoplanilhaapuracao.cc17_custoplanilhaorigem";
     $sql .= "      inner join custoplanilha  as a on   a.cc15_sequencial = custoplanilhaapuracao.cc17_custoplanilha";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = custoplanoanalitica.cc04_coddepto";
     $sql .= "      inner join custoplano  on  custoplano.cc01_sequencial = custoplanoanalitica.cc04_custoplano";
     $sql2 = "";
     if($dbwhere==""){
       if($cc17_sequencial!=null ){
         $sql2 .= " where custoplanilhaapuracao.cc17_sequencial = $cc17_sequencial "; 
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
   function sql_query_file ( $cc17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplanilhaapuracao ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc17_sequencial!=null ){
         $sql2 .= " where custoplanilhaapuracao.cc17_sequencial = $cc17_sequencial "; 
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
  
 function sql_query_custo ( $cc19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplanilhaapuracao ";
     $sql .= "      left join custoplanilhaapuracaoelemento  on  custoplanilhaapuracao.cc17_sequencial = custoplanilhaapuracaoelemento.cc19_custoplanilhaapuracao";
     $sql .= "      left join orcelemento  on  orcelemento.o56_codele = custoplanilhaapuracaoelemento.cc19_codele and  orcelemento.o56_anousu = custoplanilhaapuracaoelemento.cc19_anousu";
     $sql .= "      inner join custoplanoanalitica  on  custoplanoanalitica.cc04_sequencial = custoplanilhaapuracao.cc17_custoplanoanalitica";
     $sql .= "      inner join custoplanilhaorigem  on  custoplanilhaorigem.cc14_sequencial = custoplanilhaapuracao.cc17_custoplanilhaorigem";
     $sql .= "      inner join custoplanilha        on  cc15_sequencial = custoplanilhaapuracao.cc17_custoplanilha";
     $sql .= "      left  join custoplanilhacustoapropria     on  cc17_sequencial = cc18_custoplanilhaapuracao";
     $sql .= "      left  join custoplanilhamatordemitem      on  cc17_sequencial = cc20_custoplanilhaapuracao";
     $sql .= "      left  join custoplanilhaapuracaolocaltrab on  cc17_sequencial = cc21_custoplanilhaapuracao";
     $sql2 = "";
     if($dbwhere==""){
       if($cc19_sequencial!=null ){
         $sql2 .= " where custoplanilhaapuracao.cc17_sequencial = $cc19_sequencial "; 
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