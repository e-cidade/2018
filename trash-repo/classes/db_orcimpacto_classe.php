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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcimpacto
class cl_orcimpacto { 
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
   var $o90_codimp = 0; 
   var $o90_codperiodo = 0; 
   var $o90_anoexe = 0; 
   var $o90_orgao = 0; 
   var $o90_unidade = 0; 
   var $o90_funcao = 0; 
   var $o90_subfuncao = 0; 
   var $o90_programa = 0; 
   var $o90_programatxt = null; 
   var $o90_acao = 0; 
   var $o90_acaotxt = null; 
   var $o90_unimed = null; 
   var $o90_produto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o90_codimp = int4 = Código 
                 o90_codperiodo = int8 = Período 
                 o90_anoexe = int4 = Exercício 
                 o90_orgao = int4 = Órgão 
                 o90_unidade = int4 = Unidade 
                 o90_funcao = int4 = Função 
                 o90_subfuncao = int4 = Sub Função 
                 o90_programa = int4 = Programa 
                 o90_programatxt = text = Descrição Programa 
                 o90_acao = int4 = Projetos / Atividades 
                 o90_acaotxt = text = Descrição Ação 
                 o90_unimed = varchar(15) = Unidade Medida 
                 o90_produto = int4 = Produto 
                 ";
   //funcao construtor da classe 
   function cl_orcimpacto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcimpacto"); 
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
       $this->o90_codimp = ($this->o90_codimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_codimp"]:$this->o90_codimp);
       $this->o90_codperiodo = ($this->o90_codperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_codperiodo"]:$this->o90_codperiodo);
       $this->o90_anoexe = ($this->o90_anoexe == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_anoexe"]:$this->o90_anoexe);
       $this->o90_orgao = ($this->o90_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_orgao"]:$this->o90_orgao);
       $this->o90_unidade = ($this->o90_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_unidade"]:$this->o90_unidade);
       $this->o90_funcao = ($this->o90_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_funcao"]:$this->o90_funcao);
       $this->o90_subfuncao = ($this->o90_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_subfuncao"]:$this->o90_subfuncao);
       $this->o90_programa = ($this->o90_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_programa"]:$this->o90_programa);
       $this->o90_programatxt = ($this->o90_programatxt == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_programatxt"]:$this->o90_programatxt);
       $this->o90_acao = ($this->o90_acao == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_acao"]:$this->o90_acao);
       $this->o90_acaotxt = ($this->o90_acaotxt == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_acaotxt"]:$this->o90_acaotxt);
       $this->o90_unimed = ($this->o90_unimed == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_unimed"]:$this->o90_unimed);
       $this->o90_produto = ($this->o90_produto == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_produto"]:$this->o90_produto);
     }else{
       $this->o90_codimp = ($this->o90_codimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o90_codimp"]:$this->o90_codimp);
     }
   }
   // funcao para inclusao
   function incluir ($o90_codimp){ 
      $this->atualizacampos();
     if($this->o90_codperiodo == null ){ 
       $this->erro_sql = " Campo Período nao Informado.";
       $this->erro_campo = "o90_codperiodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o90_anoexe == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "o90_anoexe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o90_orgao == null ){ 
       $this->erro_sql = " Campo Órgão nao Informado.";
       $this->erro_campo = "o90_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o90_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "o90_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o90_funcao == null ){ 
       $this->erro_sql = " Campo Função nao Informado.";
       $this->erro_campo = "o90_funcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o90_subfuncao == null ){ 
       $this->erro_sql = " Campo Sub Função nao Informado.";
       $this->erro_campo = "o90_subfuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o90_programa == null ){ 
       $this->erro_sql = " Campo Programa nao Informado.";
       $this->erro_campo = "o90_programa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o90_programatxt == null ){ 
       $this->erro_sql = " Campo Descrição Programa nao Informado.";
       $this->erro_campo = "o90_programatxt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o90_acao == null ){ 
       $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
       $this->erro_campo = "o90_acao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o90_acaotxt == null ){ 
       $this->erro_sql = " Campo Descrição Ação nao Informado.";
       $this->erro_campo = "o90_acaotxt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o90_unimed == null ){ 
       $this->erro_sql = " Campo Unidade Medida nao Informado.";
       $this->erro_campo = "o90_unimed";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o90_produto == null ){ 
       $this->erro_sql = " Campo Produto nao Informado.";
       $this->erro_campo = "o90_produto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o90_codimp == "" || $o90_codimp == null ){
       $result = db_query("select nextval('orcimpacto_o90_codimp_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcimpacto_o90_codimp_seq do campo: o90_codimp"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o90_codimp = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcimpacto_o90_codimp_seq");
       if(($result != false) && (pg_result($result,0,0) < $o90_codimp)){
         $this->erro_sql = " Campo o90_codimp maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o90_codimp = $o90_codimp; 
       }
     }
     if(($this->o90_codimp == null) || ($this->o90_codimp == "") ){ 
       $this->erro_sql = " Campo o90_codimp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcimpacto(
                                       o90_codimp 
                                      ,o90_codperiodo 
                                      ,o90_anoexe 
                                      ,o90_orgao 
                                      ,o90_unidade 
                                      ,o90_funcao 
                                      ,o90_subfuncao 
                                      ,o90_programa 
                                      ,o90_programatxt 
                                      ,o90_acao 
                                      ,o90_acaotxt 
                                      ,o90_unimed 
                                      ,o90_produto 
                       )
                values (
                                $this->o90_codimp 
                               ,$this->o90_codperiodo 
                               ,$this->o90_anoexe 
                               ,$this->o90_orgao 
                               ,$this->o90_unidade 
                               ,$this->o90_funcao 
                               ,$this->o90_subfuncao 
                               ,$this->o90_programa 
                               ,'$this->o90_programatxt' 
                               ,$this->o90_acao 
                               ,'$this->o90_acaotxt' 
                               ,'$this->o90_unimed' 
                               ,$this->o90_produto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Impacto Orçamentário ($this->o90_codimp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Impacto Orçamentário já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Impacto Orçamentário ($this->o90_codimp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o90_codimp;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o90_codimp));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6629,'$this->o90_codimp','I')");
       $resac = db_query("insert into db_acount values($acount,1088,6629,'','".AddSlashes(pg_result($resaco,0,'o90_codimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1088,6658,'','".AddSlashes(pg_result($resaco,0,'o90_codperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1088,6630,'','".AddSlashes(pg_result($resaco,0,'o90_anoexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1088,6631,'','".AddSlashes(pg_result($resaco,0,'o90_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1088,6632,'','".AddSlashes(pg_result($resaco,0,'o90_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1088,6633,'','".AddSlashes(pg_result($resaco,0,'o90_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1088,6634,'','".AddSlashes(pg_result($resaco,0,'o90_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1088,6635,'','".AddSlashes(pg_result($resaco,0,'o90_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1088,6636,'','".AddSlashes(pg_result($resaco,0,'o90_programatxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1088,6637,'','".AddSlashes(pg_result($resaco,0,'o90_acao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1088,6638,'','".AddSlashes(pg_result($resaco,0,'o90_acaotxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1088,6640,'','".AddSlashes(pg_result($resaco,0,'o90_unimed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1088,6639,'','".AddSlashes(pg_result($resaco,0,'o90_produto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o90_codimp=null) { 
      $this->atualizacampos();
     $sql = " update orcimpacto set ";
     $virgula = "";
     if(trim($this->o90_codimp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_codimp"])){ 
       $sql  .= $virgula." o90_codimp = $this->o90_codimp ";
       $virgula = ",";
       if(trim($this->o90_codimp) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o90_codimp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o90_codperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_codperiodo"])){ 
       $sql  .= $virgula." o90_codperiodo = $this->o90_codperiodo ";
       $virgula = ",";
       if(trim($this->o90_codperiodo) == null ){ 
         $this->erro_sql = " Campo Período nao Informado.";
         $this->erro_campo = "o90_codperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o90_anoexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_anoexe"])){ 
       $sql  .= $virgula." o90_anoexe = $this->o90_anoexe ";
       $virgula = ",";
       if(trim($this->o90_anoexe) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o90_anoexe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o90_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_orgao"])){ 
       $sql  .= $virgula." o90_orgao = $this->o90_orgao ";
       $virgula = ",";
       if(trim($this->o90_orgao) == null ){ 
         $this->erro_sql = " Campo Órgão nao Informado.";
         $this->erro_campo = "o90_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o90_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_unidade"])){ 
       $sql  .= $virgula." o90_unidade = $this->o90_unidade ";
       $virgula = ",";
       if(trim($this->o90_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "o90_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o90_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_funcao"])){ 
       $sql  .= $virgula." o90_funcao = $this->o90_funcao ";
       $virgula = ",";
       if(trim($this->o90_funcao) == null ){ 
         $this->erro_sql = " Campo Função nao Informado.";
         $this->erro_campo = "o90_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o90_subfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_subfuncao"])){ 
       $sql  .= $virgula." o90_subfuncao = $this->o90_subfuncao ";
       $virgula = ",";
       if(trim($this->o90_subfuncao) == null ){ 
         $this->erro_sql = " Campo Sub Função nao Informado.";
         $this->erro_campo = "o90_subfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o90_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_programa"])){ 
       $sql  .= $virgula." o90_programa = $this->o90_programa ";
       $virgula = ",";
       if(trim($this->o90_programa) == null ){ 
         $this->erro_sql = " Campo Programa nao Informado.";
         $this->erro_campo = "o90_programa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o90_programatxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_programatxt"])){ 
       $sql  .= $virgula." o90_programatxt = '$this->o90_programatxt' ";
       $virgula = ",";
       if(trim($this->o90_programatxt) == null ){ 
         $this->erro_sql = " Campo Descrição Programa nao Informado.";
         $this->erro_campo = "o90_programatxt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o90_acao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_acao"])){ 
       $sql  .= $virgula." o90_acao = $this->o90_acao ";
       $virgula = ",";
       if(trim($this->o90_acao) == null ){ 
         $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
         $this->erro_campo = "o90_acao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o90_acaotxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_acaotxt"])){ 
       $sql  .= $virgula." o90_acaotxt = '$this->o90_acaotxt' ";
       $virgula = ",";
       if(trim($this->o90_acaotxt) == null ){ 
         $this->erro_sql = " Campo Descrição Ação nao Informado.";
         $this->erro_campo = "o90_acaotxt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o90_unimed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_unimed"])){ 
       $sql  .= $virgula." o90_unimed = '$this->o90_unimed' ";
       $virgula = ",";
       if(trim($this->o90_unimed) == null ){ 
         $this->erro_sql = " Campo Unidade Medida nao Informado.";
         $this->erro_campo = "o90_unimed";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o90_produto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o90_produto"])){ 
       $sql  .= $virgula." o90_produto = $this->o90_produto ";
       $virgula = ",";
       if(trim($this->o90_produto) == null ){ 
         $this->erro_sql = " Campo Produto nao Informado.";
         $this->erro_campo = "o90_produto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o90_codimp!=null){
       $sql .= " o90_codimp = $this->o90_codimp";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o90_codimp));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6629,'$this->o90_codimp','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_codimp"]))
           $resac = db_query("insert into db_acount values($acount,1088,6629,'".AddSlashes(pg_result($resaco,$conresaco,'o90_codimp'))."','$this->o90_codimp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_codperiodo"]))
           $resac = db_query("insert into db_acount values($acount,1088,6658,'".AddSlashes(pg_result($resaco,$conresaco,'o90_codperiodo'))."','$this->o90_codperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_anoexe"]))
           $resac = db_query("insert into db_acount values($acount,1088,6630,'".AddSlashes(pg_result($resaco,$conresaco,'o90_anoexe'))."','$this->o90_anoexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_orgao"]))
           $resac = db_query("insert into db_acount values($acount,1088,6631,'".AddSlashes(pg_result($resaco,$conresaco,'o90_orgao'))."','$this->o90_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_unidade"]))
           $resac = db_query("insert into db_acount values($acount,1088,6632,'".AddSlashes(pg_result($resaco,$conresaco,'o90_unidade'))."','$this->o90_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_funcao"]))
           $resac = db_query("insert into db_acount values($acount,1088,6633,'".AddSlashes(pg_result($resaco,$conresaco,'o90_funcao'))."','$this->o90_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_subfuncao"]))
           $resac = db_query("insert into db_acount values($acount,1088,6634,'".AddSlashes(pg_result($resaco,$conresaco,'o90_subfuncao'))."','$this->o90_subfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_programa"]))
           $resac = db_query("insert into db_acount values($acount,1088,6635,'".AddSlashes(pg_result($resaco,$conresaco,'o90_programa'))."','$this->o90_programa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_programatxt"]))
           $resac = db_query("insert into db_acount values($acount,1088,6636,'".AddSlashes(pg_result($resaco,$conresaco,'o90_programatxt'))."','$this->o90_programatxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_acao"]))
           $resac = db_query("insert into db_acount values($acount,1088,6637,'".AddSlashes(pg_result($resaco,$conresaco,'o90_acao'))."','$this->o90_acao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_acaotxt"]))
           $resac = db_query("insert into db_acount values($acount,1088,6638,'".AddSlashes(pg_result($resaco,$conresaco,'o90_acaotxt'))."','$this->o90_acaotxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_unimed"]))
           $resac = db_query("insert into db_acount values($acount,1088,6640,'".AddSlashes(pg_result($resaco,$conresaco,'o90_unimed'))."','$this->o90_unimed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o90_produto"]))
           $resac = db_query("insert into db_acount values($acount,1088,6639,'".AddSlashes(pg_result($resaco,$conresaco,'o90_produto'))."','$this->o90_produto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Impacto Orçamentário nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o90_codimp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Impacto Orçamentário nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o90_codimp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o90_codimp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o90_codimp=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o90_codimp));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6629,'$o90_codimp','E')");
         $resac = db_query("insert into db_acount values($acount,1088,6629,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_codimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1088,6658,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_codperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1088,6630,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_anoexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1088,6631,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1088,6632,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1088,6633,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1088,6634,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1088,6635,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1088,6636,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_programatxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1088,6637,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_acao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1088,6638,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_acaotxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1088,6640,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_unimed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1088,6639,'','".AddSlashes(pg_result($resaco,$iresaco,'o90_produto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcimpacto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o90_codimp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o90_codimp = $o90_codimp ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Impacto Orçamentário nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o90_codimp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Impacto Orçamentário nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o90_codimp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o90_codimp;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcimpacto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   function sql_query ( $o90_codimp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpacto ";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcimpacto.o90_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcimpacto.o90_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcimpacto.o90_anoexe and  orcprograma.o54_programa = orcimpacto.o90_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcimpacto.o90_anoexe and  orcprojativ.o55_projativ = orcimpacto.o90_acao";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcimpacto.o90_anoexe and  orcorgao.o40_orgao = orcimpacto.o90_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcimpacto.o90_anoexe and  orcunidade.o41_orgao = orcimpacto.o90_orgao and  orcunidade.o41_unidade = orcimpacto.o90_unidade";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcimpacto.o90_produto";
     $sql .= "      inner join orcimpactoperiodo  on  orcimpactoperiodo.o96_codperiodo = orcimpacto.o90_codperiodo";
     $sql .= "      inner join db_config  as b on   b.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join db_config  as c on   c.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join db_config  as d on   d.codigo = orcorgao.o40_instit";
     $sql .= "      inner join db_config  as d on   d.codigo = orcorgao.o40_instit";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcunidade.o41_anousu and   d.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcunidade.o41_anousu and   d.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcunidade.o41_anousu and   d.o40_orgao = orcunidade.o41_orgao";
     $sql2 = "";
     if($dbwhere==""){
       if($o90_codimp!=null ){
         $sql2 .= " where orcimpacto.o90_codimp = $o90_codimp "; 
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

   function sql_query_file ( $o90_codimp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpacto ";
     $sql2 = "";
     if($dbwhere==""){
       if($o90_codimp!=null ){
         $sql2 .= " where orcimpacto.o90_codimp = $o90_codimp "; 
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
   function sql_query_compl ( $o90_codimp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpacto ";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcimpacto.o90_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcimpacto.o90_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcimpacto.o90_anoexe and  orcprograma.o54_programa = orcimpacto.o90_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcimpacto.o90_anoexe and  orcprojativ.o55_projativ = orcimpacto.o90_acao";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcimpacto.o90_anoexe and  orcorgao.o40_orgao = orcimpacto.o90_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcimpacto.o90_anoexe and  orcunidade.o41_orgao = orcimpacto.o90_orgao and  orcunidade.o41_unidade = orcimpacto.o90_unidade";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcimpacto.o90_produto";
     $sql .= "      inner join orcimpactoperiodo  on  orcimpactoperiodo.o96_codperiodo = orcimpacto.o90_codperiodo";
     $sql2 = "";
     if($dbwhere==""){
       if($o90_codimp!=null ){
         $sql2 .= " where orcimpacto.o90_codimp = $o90_codimp "; 
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