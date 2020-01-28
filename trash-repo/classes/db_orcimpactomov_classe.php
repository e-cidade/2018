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
//CLASSE DA ENTIDADE orcimpactomov
class cl_orcimpactomov { 
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
   var $o63_codimpmov = 0; 
   var $o63_codperiodo = 0; 
   var $o63_anoexe = 0; 
   var $o63_orgao = 0; 
   var $o63_unidade = 0; 
   var $o63_funcao = 0; 
   var $o63_subfuncao = 0; 
   var $o63_programa = 0; 
   var $o63_programatxt = null; 
   var $o63_acao = 0; 
   var $o63_acaotxt = null; 
   var $o63_unimed = null; 
   var $o63_produto = 0; 
   var $o63_codimpger = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o63_codimpmov = int8 = Código 
                 o63_codperiodo = int8 = Período 
                 o63_anoexe = int4 = Exercício 
                 o63_orgao = int4 = Código Orgão 
                 o63_unidade = int4 = Unidade 
                 o63_funcao = int4 = Função 
                 o63_subfuncao = int4 = Sub Função 
                 o63_programa = int4 = Programa 
                 o63_programatxt = text = Descr. Programa 
                 o63_acao = int4 = Projetos / Atividades 
                 o63_acaotxt = text = Descr. Ação 
                 o63_unimed = varchar(15) = Uni. Medida 
                 o63_produto = int8 = Produto 
                 o63_codimpger = int8 = Código 
                 ";
   //funcao construtor da classe 
   function cl_orcimpactomov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcimpactomov"); 
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
       $this->o63_codimpmov = ($this->o63_codimpmov == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_codimpmov"]:$this->o63_codimpmov);
       $this->o63_codperiodo = ($this->o63_codperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_codperiodo"]:$this->o63_codperiodo);
       $this->o63_anoexe = ($this->o63_anoexe == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_anoexe"]:$this->o63_anoexe);
       $this->o63_orgao = ($this->o63_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_orgao"]:$this->o63_orgao);
       $this->o63_unidade = ($this->o63_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_unidade"]:$this->o63_unidade);
       $this->o63_funcao = ($this->o63_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_funcao"]:$this->o63_funcao);
       $this->o63_subfuncao = ($this->o63_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_subfuncao"]:$this->o63_subfuncao);
       $this->o63_programa = ($this->o63_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_programa"]:$this->o63_programa);
       $this->o63_programatxt = ($this->o63_programatxt == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_programatxt"]:$this->o63_programatxt);
       $this->o63_acao = ($this->o63_acao == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_acao"]:$this->o63_acao);
       $this->o63_acaotxt = ($this->o63_acaotxt == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_acaotxt"]:$this->o63_acaotxt);
       $this->o63_unimed = ($this->o63_unimed == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_unimed"]:$this->o63_unimed);
       $this->o63_produto = ($this->o63_produto == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_produto"]:$this->o63_produto);
       $this->o63_codimpger = ($this->o63_codimpger == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_codimpger"]:$this->o63_codimpger);
     }else{
       $this->o63_codimpmov = ($this->o63_codimpmov == ""?@$GLOBALS["HTTP_POST_VARS"]["o63_codimpmov"]:$this->o63_codimpmov);
     }
   }
   // funcao para inclusao
   function incluir ($o63_codimpmov){ 
      $this->atualizacampos();
     if($this->o63_codperiodo == null ){ 
       $this->erro_sql = " Campo Período nao Informado.";
       $this->erro_campo = "o63_codperiodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o63_anoexe == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "o63_anoexe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o63_orgao == null ){ 
       $this->erro_sql = " Campo Código Orgão nao Informado.";
       $this->erro_campo = "o63_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o63_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "o63_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o63_funcao == null ){ 
       $this->erro_sql = " Campo Função nao Informado.";
       $this->erro_campo = "o63_funcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o63_subfuncao == null ){ 
       $this->erro_sql = " Campo Sub Função nao Informado.";
       $this->erro_campo = "o63_subfuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o63_programa == null ){ 
       $this->erro_sql = " Campo Programa nao Informado.";
       $this->erro_campo = "o63_programa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o63_acao == null ){ 
       $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
       $this->erro_campo = "o63_acao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o63_unimed == null ){ 
       $this->erro_sql = " Campo Uni. Medida nao Informado.";
       $this->erro_campo = "o63_unimed";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o63_produto == null ){ 
       $this->erro_sql = " Campo Produto nao Informado.";
       $this->erro_campo = "o63_produto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o63_codimpger == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "o63_codimpger";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o63_codimpmov == "" || $o63_codimpmov == null ){
       $result = db_query("select nextval('orcimpactomov_o63_codimpmov_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcimpactomov_o63_codimpmov_seq do campo: o63_codimpmov"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o63_codimpmov = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcimpactomov_o63_codimpmov_seq");
       if(($result != false) && (pg_result($result,0,0) < $o63_codimpmov)){
         $this->erro_sql = " Campo o63_codimpmov maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o63_codimpmov = $o63_codimpmov; 
       }
     }
     if(($this->o63_codimpmov == null) || ($this->o63_codimpmov == "") ){ 
       $this->erro_sql = " Campo o63_codimpmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcimpactomov(
                                       o63_codimpmov 
                                      ,o63_codperiodo 
                                      ,o63_anoexe 
                                      ,o63_orgao 
                                      ,o63_unidade 
                                      ,o63_funcao 
                                      ,o63_subfuncao 
                                      ,o63_programa 
                                      ,o63_programatxt 
                                      ,o63_acao 
                                      ,o63_acaotxt 
                                      ,o63_unimed 
                                      ,o63_produto 
                                      ,o63_codimpger 
                       )
                values (
                                $this->o63_codimpmov 
                               ,$this->o63_codperiodo 
                               ,$this->o63_anoexe 
                               ,$this->o63_orgao 
                               ,$this->o63_unidade 
                               ,$this->o63_funcao 
                               ,$this->o63_subfuncao 
                               ,$this->o63_programa 
                               ,'$this->o63_programatxt' 
                               ,$this->o63_acao 
                               ,'$this->o63_acaotxt' 
                               ,'$this->o63_unimed' 
                               ,$this->o63_produto 
                               ,$this->o63_codimpger 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentos dos impactos ($this->o63_codimpmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentos dos impactos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentos dos impactos ($this->o63_codimpmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o63_codimpmov;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o63_codimpmov));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6667,'$this->o63_codimpmov','I')");
       $resac = db_query("insert into db_acount values($acount,1095,6667,'','".AddSlashes(pg_result($resaco,0,'o63_codimpmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6668,'','".AddSlashes(pg_result($resaco,0,'o63_codperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6669,'','".AddSlashes(pg_result($resaco,0,'o63_anoexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6670,'','".AddSlashes(pg_result($resaco,0,'o63_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6671,'','".AddSlashes(pg_result($resaco,0,'o63_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6672,'','".AddSlashes(pg_result($resaco,0,'o63_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6673,'','".AddSlashes(pg_result($resaco,0,'o63_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6674,'','".AddSlashes(pg_result($resaco,0,'o63_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6675,'','".AddSlashes(pg_result($resaco,0,'o63_programatxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6676,'','".AddSlashes(pg_result($resaco,0,'o63_acao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6677,'','".AddSlashes(pg_result($resaco,0,'o63_acaotxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6678,'','".AddSlashes(pg_result($resaco,0,'o63_unimed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6679,'','".AddSlashes(pg_result($resaco,0,'o63_produto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1095,6733,'','".AddSlashes(pg_result($resaco,0,'o63_codimpger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o63_codimpmov=null) { 
      $this->atualizacampos();
     $sql = " update orcimpactomov set ";
     $virgula = "";
     if(trim($this->o63_codimpmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_codimpmov"])){ 
       $sql  .= $virgula." o63_codimpmov = $this->o63_codimpmov ";
       $virgula = ",";
       if(trim($this->o63_codimpmov) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o63_codimpmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o63_codperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_codperiodo"])){ 
       $sql  .= $virgula." o63_codperiodo = $this->o63_codperiodo ";
       $virgula = ",";
       if(trim($this->o63_codperiodo) == null ){ 
         $this->erro_sql = " Campo Período nao Informado.";
         $this->erro_campo = "o63_codperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o63_anoexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_anoexe"])){ 
       $sql  .= $virgula." o63_anoexe = $this->o63_anoexe ";
       $virgula = ",";
       if(trim($this->o63_anoexe) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o63_anoexe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o63_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_orgao"])){ 
       $sql  .= $virgula." o63_orgao = $this->o63_orgao ";
       $virgula = ",";
       if(trim($this->o63_orgao) == null ){ 
         $this->erro_sql = " Campo Código Orgão nao Informado.";
         $this->erro_campo = "o63_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o63_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_unidade"])){ 
       $sql  .= $virgula." o63_unidade = $this->o63_unidade ";
       $virgula = ",";
       if(trim($this->o63_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "o63_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o63_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_funcao"])){ 
       $sql  .= $virgula." o63_funcao = $this->o63_funcao ";
       $virgula = ",";
       if(trim($this->o63_funcao) == null ){ 
         $this->erro_sql = " Campo Função nao Informado.";
         $this->erro_campo = "o63_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o63_subfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_subfuncao"])){ 
       $sql  .= $virgula." o63_subfuncao = $this->o63_subfuncao ";
       $virgula = ",";
       if(trim($this->o63_subfuncao) == null ){ 
         $this->erro_sql = " Campo Sub Função nao Informado.";
         $this->erro_campo = "o63_subfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o63_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_programa"])){ 
       $sql  .= $virgula." o63_programa = $this->o63_programa ";
       $virgula = ",";
       if(trim($this->o63_programa) == null ){ 
         $this->erro_sql = " Campo Programa nao Informado.";
         $this->erro_campo = "o63_programa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o63_programatxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_programatxt"])){ 
       $sql  .= $virgula." o63_programatxt = '$this->o63_programatxt' ";
       $virgula = ",";
     }
     if(trim($this->o63_acao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_acao"])){ 
       $sql  .= $virgula." o63_acao = $this->o63_acao ";
       $virgula = ",";
       if(trim($this->o63_acao) == null ){ 
         $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
         $this->erro_campo = "o63_acao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o63_acaotxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_acaotxt"])){ 
       $sql  .= $virgula." o63_acaotxt = '$this->o63_acaotxt' ";
       $virgula = ",";
     }
     if(trim($this->o63_unimed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_unimed"])){ 
       $sql  .= $virgula." o63_unimed = '$this->o63_unimed' ";
       $virgula = ",";
       if(trim($this->o63_unimed) == null ){ 
         $this->erro_sql = " Campo Uni. Medida nao Informado.";
         $this->erro_campo = "o63_unimed";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o63_produto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_produto"])){ 
       $sql  .= $virgula." o63_produto = $this->o63_produto ";
       $virgula = ",";
       if(trim($this->o63_produto) == null ){ 
         $this->erro_sql = " Campo Produto nao Informado.";
         $this->erro_campo = "o63_produto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o63_codimpger)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o63_codimpger"])){ 
       $sql  .= $virgula." o63_codimpger = $this->o63_codimpger ";
       $virgula = ",";
       if(trim($this->o63_codimpger) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o63_codimpger";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o63_codimpmov!=null){
       $sql .= " o63_codimpmov = $this->o63_codimpmov";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o63_codimpmov));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6667,'$this->o63_codimpmov','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_codimpmov"]))
           $resac = db_query("insert into db_acount values($acount,1095,6667,'".AddSlashes(pg_result($resaco,$conresaco,'o63_codimpmov'))."','$this->o63_codimpmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_codperiodo"]))
           $resac = db_query("insert into db_acount values($acount,1095,6668,'".AddSlashes(pg_result($resaco,$conresaco,'o63_codperiodo'))."','$this->o63_codperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_anoexe"]))
           $resac = db_query("insert into db_acount values($acount,1095,6669,'".AddSlashes(pg_result($resaco,$conresaco,'o63_anoexe'))."','$this->o63_anoexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_orgao"]))
           $resac = db_query("insert into db_acount values($acount,1095,6670,'".AddSlashes(pg_result($resaco,$conresaco,'o63_orgao'))."','$this->o63_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_unidade"]))
           $resac = db_query("insert into db_acount values($acount,1095,6671,'".AddSlashes(pg_result($resaco,$conresaco,'o63_unidade'))."','$this->o63_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_funcao"]))
           $resac = db_query("insert into db_acount values($acount,1095,6672,'".AddSlashes(pg_result($resaco,$conresaco,'o63_funcao'))."','$this->o63_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_subfuncao"]))
           $resac = db_query("insert into db_acount values($acount,1095,6673,'".AddSlashes(pg_result($resaco,$conresaco,'o63_subfuncao'))."','$this->o63_subfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_programa"]))
           $resac = db_query("insert into db_acount values($acount,1095,6674,'".AddSlashes(pg_result($resaco,$conresaco,'o63_programa'))."','$this->o63_programa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_programatxt"]))
           $resac = db_query("insert into db_acount values($acount,1095,6675,'".AddSlashes(pg_result($resaco,$conresaco,'o63_programatxt'))."','$this->o63_programatxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_acao"]))
           $resac = db_query("insert into db_acount values($acount,1095,6676,'".AddSlashes(pg_result($resaco,$conresaco,'o63_acao'))."','$this->o63_acao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_acaotxt"]))
           $resac = db_query("insert into db_acount values($acount,1095,6677,'".AddSlashes(pg_result($resaco,$conresaco,'o63_acaotxt'))."','$this->o63_acaotxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_unimed"]))
           $resac = db_query("insert into db_acount values($acount,1095,6678,'".AddSlashes(pg_result($resaco,$conresaco,'o63_unimed'))."','$this->o63_unimed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_produto"]))
           $resac = db_query("insert into db_acount values($acount,1095,6679,'".AddSlashes(pg_result($resaco,$conresaco,'o63_produto'))."','$this->o63_produto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o63_codimpger"]))
           $resac = db_query("insert into db_acount values($acount,1095,6733,'".AddSlashes(pg_result($resaco,$conresaco,'o63_codimpger'))."','$this->o63_codimpger',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentos dos impactos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o63_codimpmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentos dos impactos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o63_codimpmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o63_codimpmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o63_codimpmov=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o63_codimpmov));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6667,'$o63_codimpmov','E')");
         $resac = db_query("insert into db_acount values($acount,1095,6667,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_codimpmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6668,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_codperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6669,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_anoexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6670,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6671,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6672,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6673,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6674,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6675,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_programatxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6676,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_acao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6677,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_acaotxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6678,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_unimed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6679,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_produto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1095,6733,'','".AddSlashes(pg_result($resaco,$iresaco,'o63_codimpger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcimpactomov
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o63_codimpmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o63_codimpmov = $o63_codimpmov ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentos dos impactos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o63_codimpmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentos dos impactos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o63_codimpmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o63_codimpmov;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcimpactomov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   function sql_query ( $o63_codimpmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactomov ";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcimpactomov.o63_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcimpactomov.o63_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcimpactomov.o63_anoexe and  orcprograma.o54_programa = orcimpactomov.o63_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcimpactomov.o63_anoexe and  orcprojativ.o55_projativ = orcimpactomov.o63_acao";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcimpactomov.o63_anoexe and  orcorgao.o40_orgao = orcimpactomov.o63_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcimpactomov.o63_anoexe and  orcunidade.o41_orgao = orcimpactomov.o63_orgao and  orcunidade.o41_unidade = orcimpactomov.o63_unidade";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcimpactomov.o63_produto";
     $sql .= "      inner join orcimpactoperiodo  on  orcimpactoperiodo.o96_codperiodo = orcimpactomov.o63_codperiodo";
     $sql .= "      inner join orcimpactoger  on  orcimpactoger.o62_codimpger = orcimpactomov.o63_codimpger";
     $sql .= "      inner join db_config  as b on   b.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join db_config  as c on   c.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join db_config  as d on   d.codigo = orcorgao.o40_instit";
     $sql .= "      inner join db_config  as d on   d.codigo = orcorgao.o40_instit";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcunidade.o41_anousu and   d.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcunidade.o41_anousu and   d.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcunidade.o41_anousu and   d.o40_orgao = orcunidade.o41_orgao";
     $sql2 = "";
     if($dbwhere==""){
       if($o63_codimpmov!=null ){
         $sql2 .= " where orcimpactomov.o63_codimpmov = $o63_codimpmov "; 
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

   function sql_query_file ( $o63_codimpmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactomov ";
     $sql2 = "";
     if($dbwhere==""){
       if($o63_codimpmov!=null ){
         $sql2 .= " where orcimpactomov.o63_codimpmov = $o63_codimpmov "; 
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
   function sql_query_compl ( $o63_codimpmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactomov ";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcimpactomov.o63_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcimpactomov.o63_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcimpactomov.o63_anoexe and  orcprograma.o54_programa = orcimpactomov.o63_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcimpactomov.o63_anoexe and  orcprojativ.o55_projativ = orcimpactomov.o63_acao";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcimpactomov.o63_anoexe and  orcorgao.o40_orgao = orcimpactomov.o63_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcimpactomov.o63_anoexe and  orcunidade.o41_orgao = orcimpactomov.o63_orgao and  orcunidade.o41_unidade = orcimpactomov.o63_unidade";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcimpactomov.o63_produto";
     $sql .= "      inner join orcimpactoperiodo  on  orcimpactoperiodo.o96_codperiodo = orcimpactomov.o63_codperiodo";
     $sql .= "      inner join orcimpactoger  on  orcimpactoger.o62_codimpger = orcimpactomov.o63_codimpger";
     $sql2 = "";
     if($dbwhere==""){
       if($o63_codimpmov!=null ){
         $sql2 .= " where orcimpactomov.o63_codimpmov = $o63_codimpmov "; 
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