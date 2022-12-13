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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhempfolha
class cl_rhempfolha { 
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
   var $rh40_instit = 0; 
   var $rh40_anousu = 0; 
   var $rh40_mesusu = 0; 
   var $rh40_orgao = 0; 
   var $rh40_unidade = 0; 
   var $rh40_projativ = 0; 
   var $rh40_recurso = 0; 
   var $rh40_codele = 0; 
   var $rh40_rubric = null; 
   var $rh40_provento = 0; 
   var $rh40_desconto = 0; 
   var $rh40_siglaarq = null; 
   var $rh40_tipo = null; 
   var $rh40_tabprev = 0; 
   var $rh40_coddot = 0; 
   var $rh40_sequencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh40_instit = int4 = Cod. Instituição 
                 rh40_anousu = int4 = Ano 
                 rh40_mesusu = int4 = Mês 
                 rh40_orgao = int4 = Código Orgão 
                 rh40_unidade = int4 = Código Unidade 
                 rh40_projativ = int4 = Projetos / Atividades 
                 rh40_recurso = int4 = Recurso 
                 rh40_codele = int4 = Código Elemento 
                 rh40_rubric = varchar(4) = Código da Rubrica 
                 rh40_provento = float8 = Provento 
                 rh40_desconto = float8 = Desconto 
                 rh40_siglaarq = varchar(3) = Sigla 
                 rh40_tipo = varchar(1) = Tipo de empenho 
                 rh40_tabprev = int4 = Tabela de Previdência 
                 rh40_coddot = int4 = Reduzido 
                 rh40_sequencia = int4 = Sequencia do arquivo 
                 ";
   //funcao construtor da classe 
   function cl_rhempfolha() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhempfolha"); 
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
       $this->rh40_instit = ($this->rh40_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_instit"]:$this->rh40_instit);
       $this->rh40_anousu = ($this->rh40_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_anousu"]:$this->rh40_anousu);
       $this->rh40_mesusu = ($this->rh40_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_mesusu"]:$this->rh40_mesusu);
       $this->rh40_orgao = ($this->rh40_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_orgao"]:$this->rh40_orgao);
       $this->rh40_unidade = ($this->rh40_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_unidade"]:$this->rh40_unidade);
       $this->rh40_projativ = ($this->rh40_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_projativ"]:$this->rh40_projativ);
       $this->rh40_recurso = ($this->rh40_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_recurso"]:$this->rh40_recurso);
       $this->rh40_codele = ($this->rh40_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_codele"]:$this->rh40_codele);
       $this->rh40_rubric = ($this->rh40_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_rubric"]:$this->rh40_rubric);
       $this->rh40_provento = ($this->rh40_provento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_provento"]:$this->rh40_provento);
       $this->rh40_desconto = ($this->rh40_desconto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_desconto"]:$this->rh40_desconto);
       $this->rh40_siglaarq = ($this->rh40_siglaarq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_siglaarq"]:$this->rh40_siglaarq);
       $this->rh40_tipo = ($this->rh40_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_tipo"]:$this->rh40_tipo);
       $this->rh40_tabprev = ($this->rh40_tabprev == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_tabprev"]:$this->rh40_tabprev);
       $this->rh40_coddot = ($this->rh40_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_coddot"]:$this->rh40_coddot);
       $this->rh40_sequencia = ($this->rh40_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_sequencia"]:$this->rh40_sequencia);
     }else{
       $this->rh40_instit = ($this->rh40_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_instit"]:$this->rh40_instit);
       $this->rh40_anousu = ($this->rh40_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_anousu"]:$this->rh40_anousu);
       $this->rh40_mesusu = ($this->rh40_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_mesusu"]:$this->rh40_mesusu);
       $this->rh40_orgao = ($this->rh40_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_orgao"]:$this->rh40_orgao);
       $this->rh40_unidade = ($this->rh40_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_unidade"]:$this->rh40_unidade);
       $this->rh40_projativ = ($this->rh40_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_projativ"]:$this->rh40_projativ);
       $this->rh40_recurso = ($this->rh40_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_recurso"]:$this->rh40_recurso);
       $this->rh40_codele = ($this->rh40_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_codele"]:$this->rh40_codele);
       $this->rh40_rubric = ($this->rh40_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_rubric"]:$this->rh40_rubric);
       $this->rh40_siglaarq = ($this->rh40_siglaarq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh40_siglaarq"]:$this->rh40_siglaarq);
     }
   }
   // funcao para inclusao
   function incluir ($rh40_anousu,$rh40_mesusu,$rh40_orgao,$rh40_unidade,$rh40_projativ,$rh40_recurso,$rh40_codele,$rh40_rubric,$rh40_siglaarq,$rh40_instit){ 
      $this->atualizacampos();
     if($this->rh40_provento == null ){ 
       $this->erro_sql = " Campo Provento nao Informado.";
       $this->erro_campo = "rh40_provento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh40_desconto == null ){ 
       $this->rh40_desconto = "0";
     }
     if($this->rh40_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de empenho nao Informado.";
       $this->erro_campo = "rh40_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh40_tabprev == null ){ 
       $this->erro_sql = " Campo Tabela de Previdência nao Informado.";
       $this->erro_campo = "rh40_tabprev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh40_coddot == null ){ 
       $this->erro_sql = " Campo Reduzido nao Informado.";
       $this->erro_campo = "rh40_coddot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh40_sequencia == null ){ 
       $this->erro_sql = " Campo Sequencia do arquivo nao Informado.";
       $this->erro_campo = "rh40_sequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh40_anousu = $rh40_anousu; 
       $this->rh40_mesusu = $rh40_mesusu; 
       $this->rh40_orgao = $rh40_orgao; 
       $this->rh40_unidade = $rh40_unidade; 
       $this->rh40_projativ = $rh40_projativ; 
       $this->rh40_recurso = $rh40_recurso; 
       $this->rh40_codele = $rh40_codele; 
       $this->rh40_rubric = $rh40_rubric; 
       $this->rh40_siglaarq = $rh40_siglaarq;
       $this->rh40_instit = $rh40_instit; 
     if(($this->rh40_anousu == null) || ($this->rh40_anousu == "") ){ 
       $this->erro_sql = " Campo rh40_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh40_mesusu == null) || ($this->rh40_mesusu == "") ){ 
       $this->erro_sql = " Campo rh40_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh40_orgao == null) || ($this->rh40_orgao == "") ){ 
       $this->erro_sql = " Campo rh40_orgao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh40_unidade == null) || ($this->rh40_unidade == "") ){ 
       $this->erro_sql = " Campo rh40_unidade nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh40_projativ == null) || ($this->rh40_projativ == "") ){ 
       $this->erro_sql = " Campo rh40_projativ nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh40_recurso == null) || ($this->rh40_recurso == "") ){ 
       $this->erro_sql = " Campo rh40_recurso nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh40_codele == null) || ($this->rh40_codele == "") ){ 
       $this->erro_sql = " Campo rh40_codele nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh40_rubric == null) || ($this->rh40_rubric == "") ){ 
       $this->erro_sql = " Campo rh40_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh40_siglaarq == null) || ($this->rh40_siglaarq == "") ){ 
       $this->erro_sql = " Campo rh40_siglaarq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh40_instit == null) || ($this->rh40_instit == "") ){ 
       $this->erro_sql = " Campo rh40_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhempfolha(
                                       rh40_instit 
                                      ,rh40_anousu 
                                      ,rh40_mesusu 
                                      ,rh40_orgao 
                                      ,rh40_unidade 
                                      ,rh40_projativ 
                                      ,rh40_recurso 
                                      ,rh40_codele 
                                      ,rh40_rubric 
                                      ,rh40_provento 
                                      ,rh40_desconto 
                                      ,rh40_siglaarq 
                                      ,rh40_tipo 
                                      ,rh40_tabprev 
                                      ,rh40_coddot 
                                      ,rh40_sequencia 
                       )
                values (
                                $this->rh40_instit 
                               ,$this->rh40_anousu 
                               ,$this->rh40_mesusu 
                               ,$this->rh40_orgao 
                               ,$this->rh40_unidade 
                               ,$this->rh40_projativ 
                               ,$this->rh40_recurso 
                               ,$this->rh40_codele 
                               ,'$this->rh40_rubric' 
                               ,$this->rh40_provento 
                               ,$this->rh40_desconto 
                               ,'$this->rh40_siglaarq' 
                               ,'$this->rh40_tipo' 
                               ,$this->rh40_tabprev 
                               ,$this->rh40_coddot 
                               ,$this->rh40_sequencia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivo para a geração dos empenhos ($this->rh40_anousu."-".$this->rh40_mesusu."-".$this->rh40_orgao."-".$this->rh40_unidade."-".$this->rh40_projativ."-".$this->rh40_recurso."-".$this->rh40_codele."-".$this->rh40_rubric."-".$this->rh40_siglaarq."-".$this->rh40_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivo para a geração dos empenhos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivo para a geração dos empenhos ($this->rh40_anousu."-".$this->rh40_mesusu."-".$this->rh40_orgao."-".$this->rh40_unidade."-".$this->rh40_projativ."-".$this->rh40_recurso."-".$this->rh40_codele."-".$this->rh40_rubric."-".$this->rh40_siglaarq."-".$this->rh40_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh40_anousu."-".$this->rh40_mesusu."-".$this->rh40_orgao."-".$this->rh40_unidade."-".$this->rh40_projativ."-".$this->rh40_recurso."-".$this->rh40_codele."-".$this->rh40_rubric."-".$this->rh40_siglaarq."-".$this->rh40_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh40_anousu,$this->rh40_mesusu,$this->rh40_orgao,$this->rh40_unidade,$this->rh40_projativ,$this->rh40_recurso,$this->rh40_codele,$this->rh40_rubric,$this->rh40_siglaarq,$this->rh40_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7245,'$this->rh40_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,7246,'$this->rh40_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,7247,'$this->rh40_orgao','I')");
       $resac = db_query("insert into db_acountkey values($acount,7248,'$this->rh40_unidade','I')");
       $resac = db_query("insert into db_acountkey values($acount,7249,'$this->rh40_projativ','I')");
       $resac = db_query("insert into db_acountkey values($acount,7250,'$this->rh40_recurso','I')");
       $resac = db_query("insert into db_acountkey values($acount,7251,'$this->rh40_codele','I')");
       $resac = db_query("insert into db_acountkey values($acount,7252,'$this->rh40_rubric','I')");
       $resac = db_query("insert into db_acountkey values($acount,7255,'$this->rh40_siglaarq','I')");
       $resac = db_query("insert into db_acountkey values($acount,9867,'$this->rh40_sequencia','I')");
       $resac = db_query("insert into db_acountkey values($acount,9904,'$this->rh40_instit','I')");
       $resac = db_query("insert into db_acount values($acount,1202,9904,'','".AddSlashes(pg_result($resaco,0,'rh40_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7245,'','".AddSlashes(pg_result($resaco,0,'rh40_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7246,'','".AddSlashes(pg_result($resaco,0,'rh40_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7247,'','".AddSlashes(pg_result($resaco,0,'rh40_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7248,'','".AddSlashes(pg_result($resaco,0,'rh40_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7249,'','".AddSlashes(pg_result($resaco,0,'rh40_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7250,'','".AddSlashes(pg_result($resaco,0,'rh40_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7251,'','".AddSlashes(pg_result($resaco,0,'rh40_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7252,'','".AddSlashes(pg_result($resaco,0,'rh40_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7253,'','".AddSlashes(pg_result($resaco,0,'rh40_provento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7254,'','".AddSlashes(pg_result($resaco,0,'rh40_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7255,'','".AddSlashes(pg_result($resaco,0,'rh40_siglaarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7256,'','".AddSlashes(pg_result($resaco,0,'rh40_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7257,'','".AddSlashes(pg_result($resaco,0,'rh40_tabprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,7258,'','".AddSlashes(pg_result($resaco,0,'rh40_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1202,9867,'','".AddSlashes(pg_result($resaco,0,'rh40_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh40_anousu=null,$rh40_mesusu=null,$rh40_orgao=null,$rh40_unidade=null,$rh40_projativ=null,$rh40_recurso=null,$rh40_codele=null,$rh40_rubric=null,$rh40_siglaarq=null,$rh40_instit=null) { 
      $this->atualizacampos();
     $sql = " update rhempfolha set ";
     $virgula = "";
     if(trim($this->rh40_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_instit"])){ 
       $sql  .= $virgula." rh40_instit = $this->rh40_instit ";
       $virgula = ",";
       if(trim($this->rh40_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "rh40_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_anousu"])){ 
       $sql  .= $virgula." rh40_anousu = $this->rh40_anousu ";
       $virgula = ",";
       if(trim($this->rh40_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "rh40_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_mesusu"])){ 
       $sql  .= $virgula." rh40_mesusu = $this->rh40_mesusu ";
       $virgula = ",";
       if(trim($this->rh40_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "rh40_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_orgao"])){ 
       $sql  .= $virgula." rh40_orgao = $this->rh40_orgao ";
       $virgula = ",";
       if(trim($this->rh40_orgao) == null ){ 
         $this->erro_sql = " Campo Código Orgão nao Informado.";
         $this->erro_campo = "rh40_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_unidade"])){ 
       $sql  .= $virgula." rh40_unidade = $this->rh40_unidade ";
       $virgula = ",";
       if(trim($this->rh40_unidade) == null ){ 
         $this->erro_sql = " Campo Código Unidade nao Informado.";
         $this->erro_campo = "rh40_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_projativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_projativ"])){ 
       $sql  .= $virgula." rh40_projativ = $this->rh40_projativ ";
       $virgula = ",";
       if(trim($this->rh40_projativ) == null ){ 
         $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
         $this->erro_campo = "rh40_projativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_recurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_recurso"])){ 
       $sql  .= $virgula." rh40_recurso = $this->rh40_recurso ";
       $virgula = ",";
       if(trim($this->rh40_recurso) == null ){ 
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "rh40_recurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_codele"])){ 
       $sql  .= $virgula." rh40_codele = $this->rh40_codele ";
       $virgula = ",";
       if(trim($this->rh40_codele) == null ){ 
         $this->erro_sql = " Campo Código Elemento nao Informado.";
         $this->erro_campo = "rh40_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_rubric"])){ 
       $sql  .= $virgula." rh40_rubric = '$this->rh40_rubric' ";
       $virgula = ",";
       if(trim($this->rh40_rubric) == null ){ 
         $this->erro_sql = " Campo Código da Rubrica nao Informado.";
         $this->erro_campo = "rh40_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_provento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_provento"])){ 
       $sql  .= $virgula." rh40_provento = $this->rh40_provento ";
       $virgula = ",";
       if(trim($this->rh40_provento) == null ){ 
         $this->erro_sql = " Campo Provento nao Informado.";
         $this->erro_campo = "rh40_provento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_desconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_desconto"])){ 
        if(trim($this->rh40_desconto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh40_desconto"])){ 
           $this->rh40_desconto = "0" ; 
        } 
       $sql  .= $virgula." rh40_desconto = $this->rh40_desconto ";
       $virgula = ",";
     }
     if(trim($this->rh40_siglaarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_siglaarq"])){ 
       $sql  .= $virgula." rh40_siglaarq = '$this->rh40_siglaarq' ";
       $virgula = ",";
       if(trim($this->rh40_siglaarq) == null ){ 
         $this->erro_sql = " Campo Sigla nao Informado.";
         $this->erro_campo = "rh40_siglaarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_tipo"])){ 
       $sql  .= $virgula." rh40_tipo = '$this->rh40_tipo' ";
       $virgula = ",";
       if(trim($this->rh40_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de empenho nao Informado.";
         $this->erro_campo = "rh40_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_tabprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_tabprev"])){ 
       $sql  .= $virgula." rh40_tabprev = $this->rh40_tabprev ";
       $virgula = ",";
       if(trim($this->rh40_tabprev) == null ){ 
         $this->erro_sql = " Campo Tabela de Previdência nao Informado.";
         $this->erro_campo = "rh40_tabprev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_coddot"])){ 
       $sql  .= $virgula." rh40_coddot = $this->rh40_coddot ";
       $virgula = ",";
       if(trim($this->rh40_coddot) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "rh40_coddot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh40_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh40_sequencia"])){ 
       $sql  .= $virgula." rh40_sequencia = $this->rh40_sequencia ";
       $virgula = ",";
       if(trim($this->rh40_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequencia do arquivo nao Informado.";
         $this->erro_campo = "rh40_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh40_anousu!=null){
       $sql .= " rh40_anousu = $this->rh40_anousu";
     }
     if($rh40_mesusu!=null){
       $sql .= " and  rh40_mesusu = $this->rh40_mesusu";
     }
     if($rh40_orgao!=null){
       $sql .= " and  rh40_orgao = $this->rh40_orgao";
     }
     if($rh40_unidade!=null){
       $sql .= " and  rh40_unidade = $this->rh40_unidade";
     }
     if($rh40_projativ!=null){
       $sql .= " and  rh40_projativ = $this->rh40_projativ";
     }
     if($rh40_recurso!=null){
       $sql .= " and  rh40_recurso = $this->rh40_recurso";
     }
     if($rh40_codele!=null){
       $sql .= " and  rh40_codele = $this->rh40_codele";
     }
     if($rh40_rubric!=null){
       $sql .= " and  rh40_rubric = '$this->rh40_rubric'";
     }
     if($rh40_siglaarq!=null){
       $sql .= " and  rh40_siglaarq = '$this->rh40_siglaarq'";
     }
     if($rh40_instit!=null){
       $sql .= " and  rh40_instit = $this->rh40_instit";
     }
     if($this->rh40_sequencia!=null){
       $sql .= " and  rh40_sequencia = $this->rh40_sequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh40_anousu,$this->rh40_mesusu,$this->rh40_orgao,$this->rh40_unidade,$this->rh40_projativ,$this->rh40_recurso,$this->rh40_codele,$this->rh40_rubric,$this->rh40_siglaarq,$this->rh40_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7245,'$this->rh40_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,7246,'$this->rh40_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,7247,'$this->rh40_orgao','A')");
         $resac = db_query("insert into db_acountkey values($acount,7248,'$this->rh40_unidade','A')");
         $resac = db_query("insert into db_acountkey values($acount,7249,'$this->rh40_projativ','A')");
         $resac = db_query("insert into db_acountkey values($acount,7250,'$this->rh40_recurso','A')");
         $resac = db_query("insert into db_acountkey values($acount,7251,'$this->rh40_codele','A')");
         $resac = db_query("insert into db_acountkey values($acount,7252,'$this->rh40_rubric','A')");
         $resac = db_query("insert into db_acountkey values($acount,7255,'$this->rh40_siglaarq','A')");
         $resac = db_query("insert into db_acountkey values($acount,9867,'$this->rh40_sequencia','A')");
         $resac = db_query("insert into db_acountkey values($acount,9904,'$this->rh40_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_instit"]))
           $resac = db_query("insert into db_acount values($acount,1202,9904,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_instit'))."','$this->rh40_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1202,7245,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_anousu'))."','$this->rh40_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,1202,7246,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_mesusu'))."','$this->rh40_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_orgao"]))
           $resac = db_query("insert into db_acount values($acount,1202,7247,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_orgao'))."','$this->rh40_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_unidade"]))
           $resac = db_query("insert into db_acount values($acount,1202,7248,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_unidade'))."','$this->rh40_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_projativ"]))
           $resac = db_query("insert into db_acount values($acount,1202,7249,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_projativ'))."','$this->rh40_projativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_recurso"]))
           $resac = db_query("insert into db_acount values($acount,1202,7250,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_recurso'))."','$this->rh40_recurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_codele"]))
           $resac = db_query("insert into db_acount values($acount,1202,7251,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_codele'))."','$this->rh40_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_rubric"]))
           $resac = db_query("insert into db_acount values($acount,1202,7252,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_rubric'))."','$this->rh40_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_provento"]))
           $resac = db_query("insert into db_acount values($acount,1202,7253,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_provento'))."','$this->rh40_provento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_desconto"]))
           $resac = db_query("insert into db_acount values($acount,1202,7254,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_desconto'))."','$this->rh40_desconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_siglaarq"]))
           $resac = db_query("insert into db_acount values($acount,1202,7255,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_siglaarq'))."','$this->rh40_siglaarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1202,7256,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_tipo'))."','$this->rh40_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_tabprev"]))
           $resac = db_query("insert into db_acount values($acount,1202,7257,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_tabprev'))."','$this->rh40_tabprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_coddot"]))
           $resac = db_query("insert into db_acount values($acount,1202,7258,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_coddot'))."','$this->rh40_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh40_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1202,9867,'".AddSlashes(pg_result($resaco,$conresaco,'rh40_sequencia'))."','$this->rh40_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo para a geração dos empenhos nao Alterado. Alteracao Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->rh40_anousu."-".$this->rh40_mesusu."-".$this->rh40_orgao."-".$this->rh40_unidade."-".$this->rh40_projativ."-".$this->rh40_recurso."-".$this->rh40_codele."-".$this->rh40_rubric."-".$this->rh40_siglaarq."-".$this->rh40_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo para a geração dos empenhos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh40_anousu."-".$this->rh40_mesusu."-".$this->rh40_orgao."-".$this->rh40_unidade."-".$this->rh40_projativ."-".$this->rh40_recurso."-".$this->rh40_codele."-".$this->rh40_rubric."-".$this->rh40_siglaarq."-".$this->rh40_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh40_anousu."-".$this->rh40_mesusu."-".$this->rh40_orgao."-".$this->rh40_unidade."-".$this->rh40_projativ."-".$this->rh40_recurso."-".$this->rh40_codele."-".$this->rh40_rubric."-".$this->rh40_siglaarq."-".$this->rh40_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh40_anousu=null,$rh40_mesusu=null,$rh40_orgao=null,$rh40_unidade=null,$rh40_projativ=null,$rh40_recurso=null,$rh40_codele=null,$rh40_rubric=null,$rh40_siglaarq=null,$rh40_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh40_anousu,$rh40_mesusu,$rh40_orgao,$rh40_unidade,$rh40_projativ,$rh40_recurso,$rh40_codele,$rh40_rubric,$rh40_siglaarq,$rh40_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7245,'$rh40_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,7246,'$rh40_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,7247,'$rh40_orgao','E')");
         $resac = db_query("insert into db_acountkey values($acount,7248,'$rh40_unidade','E')");
         $resac = db_query("insert into db_acountkey values($acount,7249,'$rh40_projativ','E')");
         $resac = db_query("insert into db_acountkey values($acount,7250,'$rh40_recurso','E')");
         $resac = db_query("insert into db_acountkey values($acount,7251,'$rh40_codele','E')");
         $resac = db_query("insert into db_acountkey values($acount,7252,'$rh40_rubric','E')");
         $resac = db_query("insert into db_acountkey values($acount,7255,'$rh40_siglaarq','E')");
         $resac = db_query("insert into db_acountkey values($acount,9904,'$rh40_instit','E')");
         $resac = db_query("insert into db_acount values($acount,1202,9904,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7245,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7246,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7247,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7248,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7249,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7250,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7251,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7252,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7253,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_provento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7254,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7255,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_siglaarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7256,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7257,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_tabprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,7258,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1202,9867,'','".AddSlashes(pg_result($resaco,$iresaco,'rh40_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhempfolha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh40_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh40_anousu = $rh40_anousu ";
        }
        if($rh40_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh40_mesusu = $rh40_mesusu ";
        }
        if($rh40_orgao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh40_orgao = $rh40_orgao ";
        }
        if($rh40_unidade != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh40_unidade = $rh40_unidade ";
        }
        if($rh40_projativ != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh40_projativ = $rh40_projativ ";
        }
        if($rh40_recurso != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh40_recurso = $rh40_recurso ";
        }
        if($rh40_codele != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh40_codele = $rh40_codele ";
        }
        if($rh40_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh40_rubric = '$rh40_rubric' ";
        }
        if($rh40_siglaarq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh40_siglaarq = '$rh40_siglaarq' ";
        }
        if($rh40_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh40_sequencia = $rh40_sequencia ";
        }
        if($rh40_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh40_instit = $rh40_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo para a geração dos empenhos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh40_anousu."-".$rh40_mesusu."-".$rh40_orgao."-".$rh40_unidade."-".$rh40_projativ."-".$rh40_recurso."-".$rh40_codele."-".$rh40_rubric."-".$rh40_siglaarq."-".$rh40_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo para a geração dos empenhos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh40_anousu."-".$rh40_mesusu."-".$rh40_orgao."-".$rh40_unidade."-".$rh40_projativ."-".$rh40_recurso."-".$rh40_codele."-".$rh40_rubric."-".$rh40_siglaarq."-".$rh40_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh40_anousu."-".$rh40_mesusu."-".$rh40_orgao."-".$rh40_unidade."-".$rh40_projativ."-".$rh40_recurso."-".$rh40_codele."-".$rh40_rubric."-".$rh40_siglaarq."-".$rh40_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhempfolha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh40_anousu=null,$rh40_mesusu=null,$rh40_orgao=null,$rh40_unidade=null,$rh40_projativ=null,$rh40_recurso=null,$rh40_codele=null,$rh40_rubric=null,$rh40_siglaarq=null,$rh40_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempfolha ";
     $sql .= "      inner join db_config as d on  d.codigo = rhempfolha.rh40_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhempfolha.rh40_recurso";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = rhempfolha.rh40_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = rhempfolha.rh40_anousu 
		                                       and  orcprojativ.o55_projativ = rhempfolha.rh40_projativ
																					 and  orcprojativ.o55_instit   = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = rhempfolha.rh40_anousu and  orcunidade.o41_orgao = rhempfolha.rh40_orgao and  orcunidade.o41_unidade = rhempfolha.rh40_unidade";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhempfolha.rh40_rubric
		                                      and  rhrubricas.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join db_config  as a on   a.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcunidade.o41_anousu and  orcorgao.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      inner join orcorgao  as b on   b.o40_anousu = orcunidade.o41_anousu and   b.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      inner join orcorgao  as c on   c.o40_anousu = orcunidade.o41_anousu and   c.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql2 = "";
     if($dbwhere==""){
       if($rh40_anousu!=null ){
         $sql2 .= " where rhempfolha.rh40_anousu = $rh40_anousu "; 
       } 
       if($rh40_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_mesusu = $rh40_mesusu "; 
       } 
       if($rh40_orgao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_orgao = $rh40_orgao "; 
       } 
       if($rh40_unidade!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_unidade = $rh40_unidade "; 
       } 
       if($rh40_projativ!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_projativ = $rh40_projativ "; 
       } 
       if($rh40_recurso!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_recurso = $rh40_recurso "; 
       } 
       if($rh40_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_codele = $rh40_codele "; 
       } 
       if($rh40_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_rubric = '$rh40_rubric' "; 
       } 
       if($rh40_siglaarq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_siglaarq = '$rh40_siglaarq' "; 
       } 
       if($rh40_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_instit = $rh40_instit "; 
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
   function sql_query_file ( $rh40_anousu=null,$rh40_mesusu=null,$rh40_orgao=null,$rh40_unidade=null,$rh40_projativ=null,$rh40_recurso=null,$rh40_codele=null,$rh40_rubric=null,$rh40_siglaarq=null,$rh40_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempfolha ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh40_anousu!=null ){
         $sql2 .= " where rhempfolha.rh40_anousu = $rh40_anousu "; 
       } 
       if($rh40_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_mesusu = $rh40_mesusu "; 
       } 
       if($rh40_orgao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_orgao = $rh40_orgao "; 
       } 
       if($rh40_unidade!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_unidade = $rh40_unidade "; 
       } 
       if($rh40_projativ!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_projativ = $rh40_projativ "; 
       } 
       if($rh40_recurso!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_recurso = $rh40_recurso "; 
       } 
       if($rh40_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_codele = $rh40_codele "; 
       } 
       if($rh40_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_rubric = '$rh40_rubric' "; 
       } 
       if($rh40_siglaarq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_siglaarq = '$rh40_siglaarq' "; 
       } 
       if($rh40_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_instit = $rh40_instit "; 
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
   function sql_query_rubr ( $rh40_anousu=null,$rh40_mesusu=null,$rh40_orgao=null,$rh40_unidade=null,$rh40_projativ=null,$rh40_recurso=null,$rh40_codele=null,$rh40_rubric=null,$rh40_siglaarq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempfolha ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhempfolha.rh40_rubric
		                                      and  rhrubricas.rh27_instit = ".db_getsession("DB_instit")." ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh40_anousu!=null ){
         $sql2 .= " where rhempfolha.rh40_anousu = $rh40_anousu "; 
       } 
       if($rh40_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_mesusu = $rh40_mesusu "; 
       } 
       if($rh40_orgao!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_orgao = $rh40_orgao "; 
       } 
       if($rh40_unidade!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_unidade = $rh40_unidade "; 
       } 
       if($rh40_projativ!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_projativ = $rh40_projativ "; 
       } 
       if($rh40_recurso!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_recurso = $rh40_recurso "; 
       } 
       if($rh40_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_codele = $rh40_codele "; 
       } 
       if($rh40_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_rubric = '$rh40_rubric' "; 
       } 
       if($rh40_siglaarq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhempfolha.rh40_siglaarq = '$rh40_siglaarq' "; 
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