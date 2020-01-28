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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_paragrafopadrao
class cl_db_paragrafopadrao { 
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
   var $db61_codparag = 0; 
   var $db61_descr = null; 
   var $db61_texto = null; 
   var $db61_alinha = 0; 
   var $db61_inicia = 0; 
   var $db61_espaco = 0; 
   var $db61_alinhamento = null; 
   var $db61_altura = 0; 
   var $db61_largura = 0; 
   var $db61_tipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db61_codparag = int4 = Cod. Parágrafo 
                 db61_descr = varchar(100) = Descr. Parágrafo 
                 db61_texto = text = Texto 
                 db61_alinha = int4 = Alinhamento 
                 db61_inicia = int4 = Inicio da linha 
                 db61_espaco = int4 = Espaço entre linhas 
                 db61_alinhamento = char(1) = Alinhamento 
                 db61_altura = float4 = Altura 
                 db61_largura = float4 = Largura 
                 db61_tipo = int4 = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_db_paragrafopadrao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_paragrafopadrao"); 
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
       $this->db61_codparag = ($this->db61_codparag == ""?@$GLOBALS["HTTP_POST_VARS"]["db61_codparag"]:$this->db61_codparag);
       $this->db61_descr = ($this->db61_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["db61_descr"]:$this->db61_descr);
       $this->db61_texto = ($this->db61_texto == ""?@$GLOBALS["HTTP_POST_VARS"]["db61_texto"]:$this->db61_texto);
       $this->db61_alinha = ($this->db61_alinha == ""?@$GLOBALS["HTTP_POST_VARS"]["db61_alinha"]:$this->db61_alinha);
       $this->db61_inicia = ($this->db61_inicia == ""?@$GLOBALS["HTTP_POST_VARS"]["db61_inicia"]:$this->db61_inicia);
       $this->db61_espaco = ($this->db61_espaco == ""?@$GLOBALS["HTTP_POST_VARS"]["db61_espaco"]:$this->db61_espaco);
       $this->db61_alinhamento = ($this->db61_alinhamento == ""?@$GLOBALS["HTTP_POST_VARS"]["db61_alinhamento"]:$this->db61_alinhamento);
       $this->db61_altura = ($this->db61_altura == ""?@$GLOBALS["HTTP_POST_VARS"]["db61_altura"]:$this->db61_altura);
       $this->db61_largura = ($this->db61_largura == ""?@$GLOBALS["HTTP_POST_VARS"]["db61_largura"]:$this->db61_largura);
       $this->db61_tipo = ($this->db61_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db61_tipo"]:$this->db61_tipo);
     }else{
       $this->db61_codparag = ($this->db61_codparag == ""?@$GLOBALS["HTTP_POST_VARS"]["db61_codparag"]:$this->db61_codparag);
     }
   }
   // funcao para inclusao
   function incluir ($db61_codparag){ 
      $this->atualizacampos();
     if($this->db61_descr == null ){ 
       $this->erro_sql = " Campo Descr. Parágrafo nao Informado.";
       $this->erro_campo = "db61_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db61_texto == null ){ 
       $this->erro_sql = " Campo Texto nao Informado.";
       $this->erro_campo = "db61_texto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db61_alinha == null ){ 
       $this->erro_sql = " Campo Alinhamento nao Informado.";
       $this->erro_campo = "db61_alinha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db61_inicia == null ){ 
       $this->erro_sql = " Campo Inicio da linha nao Informado.";
       $this->erro_campo = "db61_inicia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db61_espaco == null ){ 
       $this->erro_sql = " Campo Espaço entre linhas nao Informado.";
       $this->erro_campo = "db61_espaco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db61_alinhamento == null ){ 
       $this->erro_sql = " Campo Alinhamento nao Informado.";
       $this->erro_campo = "db61_alinhamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db61_altura == null ){ 
       $this->erro_sql = " Campo Altura nao Informado.";
       $this->erro_campo = "db61_altura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db61_largura == null ){ 
       $this->erro_sql = " Campo Largura nao Informado.";
       $this->erro_campo = "db61_largura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db61_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "db61_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db61_codparag == "" || $db61_codparag == null ){
       $result = db_query("select nextval('db_paragrafopadrao_db61_codparag_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_paragrafopadrao_db61_codparag_seq do campo: db61_codparag"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db61_codparag = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_paragrafopadrao_db61_codparag_seq");
       if(($result != false) && (pg_result($result,0,0) < $db61_codparag)){
         $this->erro_sql = " Campo db61_codparag maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db61_codparag = $db61_codparag; 
       }
     }
     if(($this->db61_codparag == null) || ($this->db61_codparag == "") ){ 
       $this->erro_sql = " Campo db61_codparag nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_paragrafopadrao(
                                       db61_codparag 
                                      ,db61_descr 
                                      ,db61_texto 
                                      ,db61_alinha 
                                      ,db61_inicia 
                                      ,db61_espaco 
                                      ,db61_alinhamento 
                                      ,db61_altura 
                                      ,db61_largura 
                                      ,db61_tipo 
                       )
                values (
                                $this->db61_codparag 
                               ,'$this->db61_descr' 
                               ,'$this->db61_texto' 
                               ,$this->db61_alinha 
                               ,$this->db61_inicia 
                               ,$this->db61_espaco 
                               ,'$this->db61_alinhamento' 
                               ,$this->db61_altura 
                               ,$this->db61_largura 
                               ,$this->db61_tipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de paragrafo padrões ($this->db61_codparag) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de paragrafo padrões já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de paragrafo padrões ($this->db61_codparag) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db61_codparag;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db61_codparag));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9161,'$this->db61_codparag','I')");
       $resac = db_query("insert into db_acount values($acount,1569,9161,'','".AddSlashes(pg_result($resaco,0,'db61_codparag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1569,9162,'','".AddSlashes(pg_result($resaco,0,'db61_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1569,9163,'','".AddSlashes(pg_result($resaco,0,'db61_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1569,9164,'','".AddSlashes(pg_result($resaco,0,'db61_alinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1569,9165,'','".AddSlashes(pg_result($resaco,0,'db61_inicia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1569,9166,'','".AddSlashes(pg_result($resaco,0,'db61_espaco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1569,11200,'','".AddSlashes(pg_result($resaco,0,'db61_alinhamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1569,11201,'','".AddSlashes(pg_result($resaco,0,'db61_altura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1569,11204,'','".AddSlashes(pg_result($resaco,0,'db61_largura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1569,11205,'','".AddSlashes(pg_result($resaco,0,'db61_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db61_codparag=null) { 
      $this->atualizacampos();
     $sql = " update db_paragrafopadrao set ";
     $virgula = "";
     if(trim($this->db61_codparag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db61_codparag"])){ 
       $sql  .= $virgula." db61_codparag = $this->db61_codparag ";
       $virgula = ",";
       if(trim($this->db61_codparag) == null ){ 
         $this->erro_sql = " Campo Cod. Parágrafo nao Informado.";
         $this->erro_campo = "db61_codparag";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db61_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db61_descr"])){ 
       $sql  .= $virgula." db61_descr = '$this->db61_descr' ";
       $virgula = ",";
       if(trim($this->db61_descr) == null ){ 
         $this->erro_sql = " Campo Descr. Parágrafo nao Informado.";
         $this->erro_campo = "db61_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db61_texto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db61_texto"])){ 
       $sql  .= $virgula." db61_texto = '$this->db61_texto' ";
       $virgula = ",";
       if(trim($this->db61_texto) == null ){ 
         $this->erro_sql = " Campo Texto nao Informado.";
         $this->erro_campo = "db61_texto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db61_alinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db61_alinha"])){ 
       $sql  .= $virgula." db61_alinha = $this->db61_alinha ";
       $virgula = ",";
       if(trim($this->db61_alinha) == null ){ 
         $this->erro_sql = " Campo Alinhamento nao Informado.";
         $this->erro_campo = "db61_alinha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db61_inicia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db61_inicia"])){ 
       $sql  .= $virgula." db61_inicia = $this->db61_inicia ";
       $virgula = ",";
       if(trim($this->db61_inicia) == null ){ 
         $this->erro_sql = " Campo Inicio da linha nao Informado.";
         $this->erro_campo = "db61_inicia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db61_espaco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db61_espaco"])){ 
       $sql  .= $virgula." db61_espaco = $this->db61_espaco ";
       $virgula = ",";
       if(trim($this->db61_espaco) == null ){ 
         $this->erro_sql = " Campo Espaço entre linhas nao Informado.";
         $this->erro_campo = "db61_espaco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db61_alinhamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db61_alinhamento"])){ 
       $sql  .= $virgula." db61_alinhamento = '$this->db61_alinhamento' ";
       $virgula = ",";
       if(trim($this->db61_alinhamento) == null ){ 
         $this->erro_sql = " Campo Alinhamento nao Informado.";
         $this->erro_campo = "db61_alinhamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db61_altura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db61_altura"])){ 
       $sql  .= $virgula." db61_altura = $this->db61_altura ";
       $virgula = ",";
       if(trim($this->db61_altura) == null ){ 
         $this->erro_sql = " Campo Altura nao Informado.";
         $this->erro_campo = "db61_altura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db61_largura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db61_largura"])){ 
       $sql  .= $virgula." db61_largura = $this->db61_largura ";
       $virgula = ",";
       if(trim($this->db61_largura) == null ){ 
         $this->erro_sql = " Campo Largura nao Informado.";
         $this->erro_campo = "db61_largura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db61_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db61_tipo"])){ 
       $sql  .= $virgula." db61_tipo = $this->db61_tipo ";
       $virgula = ",";
       if(trim($this->db61_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "db61_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db61_codparag!=null){
       $sql .= " db61_codparag = $this->db61_codparag";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db61_codparag));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9161,'$this->db61_codparag','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db61_codparag"]))
           $resac = db_query("insert into db_acount values($acount,1569,9161,'".AddSlashes(pg_result($resaco,$conresaco,'db61_codparag'))."','$this->db61_codparag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db61_descr"]))
           $resac = db_query("insert into db_acount values($acount,1569,9162,'".AddSlashes(pg_result($resaco,$conresaco,'db61_descr'))."','$this->db61_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db61_texto"]))
           $resac = db_query("insert into db_acount values($acount,1569,9163,'".AddSlashes(pg_result($resaco,$conresaco,'db61_texto'))."','$this->db61_texto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db61_alinha"]))
           $resac = db_query("insert into db_acount values($acount,1569,9164,'".AddSlashes(pg_result($resaco,$conresaco,'db61_alinha'))."','$this->db61_alinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db61_inicia"]))
           $resac = db_query("insert into db_acount values($acount,1569,9165,'".AddSlashes(pg_result($resaco,$conresaco,'db61_inicia'))."','$this->db61_inicia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db61_espaco"]))
           $resac = db_query("insert into db_acount values($acount,1569,9166,'".AddSlashes(pg_result($resaco,$conresaco,'db61_espaco'))."','$this->db61_espaco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db61_alinhamento"]))
           $resac = db_query("insert into db_acount values($acount,1569,11200,'".AddSlashes(pg_result($resaco,$conresaco,'db61_alinhamento'))."','$this->db61_alinhamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db61_altura"]))
           $resac = db_query("insert into db_acount values($acount,1569,11201,'".AddSlashes(pg_result($resaco,$conresaco,'db61_altura'))."','$this->db61_altura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db61_largura"]))
           $resac = db_query("insert into db_acount values($acount,1569,11204,'".AddSlashes(pg_result($resaco,$conresaco,'db61_largura'))."','$this->db61_largura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db61_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1569,11205,'".AddSlashes(pg_result($resaco,$conresaco,'db61_tipo'))."','$this->db61_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de paragrafo padrões nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db61_codparag;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de paragrafo padrões nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db61_codparag;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db61_codparag;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db61_codparag=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db61_codparag));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9161,'$db61_codparag','E')");
         $resac = db_query("insert into db_acount values($acount,1569,9161,'','".AddSlashes(pg_result($resaco,$iresaco,'db61_codparag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1569,9162,'','".AddSlashes(pg_result($resaco,$iresaco,'db61_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1569,9163,'','".AddSlashes(pg_result($resaco,$iresaco,'db61_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1569,9164,'','".AddSlashes(pg_result($resaco,$iresaco,'db61_alinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1569,9165,'','".AddSlashes(pg_result($resaco,$iresaco,'db61_inicia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1569,9166,'','".AddSlashes(pg_result($resaco,$iresaco,'db61_espaco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1569,11200,'','".AddSlashes(pg_result($resaco,$iresaco,'db61_alinhamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1569,11201,'','".AddSlashes(pg_result($resaco,$iresaco,'db61_altura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1569,11204,'','".AddSlashes(pg_result($resaco,$iresaco,'db61_largura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1569,11205,'','".AddSlashes(pg_result($resaco,$iresaco,'db61_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_paragrafopadrao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db61_codparag != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db61_codparag = $db61_codparag ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de paragrafo padrões nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db61_codparag;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de paragrafo padrões nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db61_codparag;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db61_codparag;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_paragrafopadrao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db61_codparag=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_paragrafopadrao ";
     $sql2 = "";
     if($dbwhere==""){
       if($db61_codparag!=null ){
         $sql2 .= " where db_paragrafopadrao.db61_codparag = $db61_codparag "; 
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
   function sql_query_file ( $db61_codparag=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_paragrafopadrao ";
     $sql2 = "";
     if($dbwhere==""){
       if($db61_codparag!=null ){
         $sql2 .= " where db_paragrafopadrao.db61_codparag = $db61_codparag "; 
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