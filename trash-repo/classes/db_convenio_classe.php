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
//CLASSE DA ENTIDADE convenio
class cl_convenio { 
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
   var $r56_instit = 0; 
   var $r56_codrel = null; 
   var $r56_descr = null; 
   var $r56_local = null; 
   var $r56_dirarq = null; 
   var $r56_posano = null; 
   var $r56_posmes = null; 
   var $r56_posreg = null; 
   var $r56_poseve = null; 
   var $r56_posq01 = null; 
   var $r56_vq01 = 'f'; 
   var $r56_posq02 = null; 
   var $r56_vq02 = 'f'; 
   var $r56_posq03 = null; 
   var $r56_vq03 = 'f'; 
   var $r56_linhastrailler = 0; 
   var $r56_linhasheader = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r56_instit = int4 = Cod. Instituição 
                 r56_codrel = varchar(4) = convênio 
                 r56_descr = varchar(40) = convênio 
                 r56_local = varchar(40) = leitura 
                 r56_dirarq = varchar(40) = Caminho 
                 r56_posano = varchar(6) = leitura do ano 
                 r56_posmes = varchar(6) = leitura do mês 
                 r56_posreg = varchar(6) = Servidor 
                 r56_poseve = varchar(6) = relacionamento 
                 r56_posq01 = varchar(6) = rubrica 1 
                 r56_vq01 = boolean = define se e valor ou quantidad 
                 r56_posq02 = varchar(6) = rubrica 02 
                 r56_vq02 = boolean = def.se quant02 e valor ou quan 
                 r56_posq03 = varchar(6) = rubrica 3 
                 r56_vq03 = boolean = def.se rubr.3 e valor ou qtd. 
                 r56_linhastrailler = int4 = Linhas trailler 
                 r56_linhasheader = int4 = Linhas header 
                 ";
   //funcao construtor da classe 
   function cl_convenio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("convenio"); 
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
       $this->r56_instit = ($this->r56_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_instit"]:$this->r56_instit);
       $this->r56_codrel = ($this->r56_codrel == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_codrel"]:$this->r56_codrel);
       $this->r56_descr = ($this->r56_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_descr"]:$this->r56_descr);
       $this->r56_local = ($this->r56_local == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_local"]:$this->r56_local);
       $this->r56_dirarq = ($this->r56_dirarq == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_dirarq"]:$this->r56_dirarq);
       $this->r56_posano = ($this->r56_posano == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_posano"]:$this->r56_posano);
       $this->r56_posmes = ($this->r56_posmes == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_posmes"]:$this->r56_posmes);
       $this->r56_posreg = ($this->r56_posreg == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_posreg"]:$this->r56_posreg);
       $this->r56_poseve = ($this->r56_poseve == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_poseve"]:$this->r56_poseve);
       $this->r56_posq01 = ($this->r56_posq01 == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_posq01"]:$this->r56_posq01);
       $this->r56_vq01 = ($this->r56_vq01 == "f"?@$GLOBALS["HTTP_POST_VARS"]["r56_vq01"]:$this->r56_vq01);
       $this->r56_posq02 = ($this->r56_posq02 == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_posq02"]:$this->r56_posq02);
       $this->r56_vq02 = ($this->r56_vq02 == "f"?@$GLOBALS["HTTP_POST_VARS"]["r56_vq02"]:$this->r56_vq02);
       $this->r56_posq03 = ($this->r56_posq03 == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_posq03"]:$this->r56_posq03);
       $this->r56_vq03 = ($this->r56_vq03 == "f"?@$GLOBALS["HTTP_POST_VARS"]["r56_vq03"]:$this->r56_vq03);
       $this->r56_linhastrailler = ($this->r56_linhastrailler == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_linhastrailler"]:$this->r56_linhastrailler);
       $this->r56_linhasheader = ($this->r56_linhasheader == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_linhasheader"]:$this->r56_linhasheader);
     }else{
       $this->r56_instit = ($this->r56_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_instit"]:$this->r56_instit);
       $this->r56_codrel = ($this->r56_codrel == ""?@$GLOBALS["HTTP_POST_VARS"]["r56_codrel"]:$this->r56_codrel);
     }
   }
   // funcao para inclusao
   function incluir ($r56_codrel,$r56_instit){ 
      $this->atualizacampos();
     if($this->r56_descr == null ){ 
       $this->erro_sql = " Campo convênio nao Informado.";
       $this->erro_campo = "r56_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r56_local == null ){ 
       $this->erro_sql = " Campo leitura nao Informado.";
       $this->erro_campo = "r56_local";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r56_dirarq == null ){ 
       $this->erro_sql = " Campo Caminho nao Informado.";
       $this->erro_campo = "r56_dirarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r56_vq01 == null ){ 
       $this->erro_sql = " Campo define se e valor ou quantidad nao Informado.";
       $this->erro_campo = "r56_vq01";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r56_vq02 == null ){ 
       $this->erro_sql = " Campo def.se quant02 e valor ou quan nao Informado.";
       $this->erro_campo = "r56_vq02";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r56_vq03 == null ){ 
       $this->erro_sql = " Campo def.se rubr.3 e valor ou qtd. nao Informado.";
       $this->erro_campo = "r56_vq03";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r56_linhastrailler == null ){ 
       $this->r56_linhastrailler = "0";
     }
     if($this->r56_linhasheader == null ){ 
       $this->r56_linhasheader = "0";
     }
       $this->r56_codrel = $r56_codrel; 
       $this->r56_instit = $r56_instit; 
     if(($this->r56_codrel == null) || ($this->r56_codrel == "") ){ 
       $this->erro_sql = " Campo r56_codrel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r56_instit == null) || ($this->r56_instit == "") ){ 
       $this->erro_sql = " Campo r56_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into convenio(
                                       r56_instit 
                                      ,r56_codrel 
                                      ,r56_descr 
                                      ,r56_local 
                                      ,r56_dirarq 
                                      ,r56_posano 
                                      ,r56_posmes 
                                      ,r56_posreg 
                                      ,r56_poseve 
                                      ,r56_posq01 
                                      ,r56_vq01 
                                      ,r56_posq02 
                                      ,r56_vq02 
                                      ,r56_posq03 
                                      ,r56_vq03 
                                      ,r56_linhastrailler 
                                      ,r56_linhasheader 
                       )
                values (
                                $this->r56_instit 
                               ,'$this->r56_codrel' 
                               ,'$this->r56_descr' 
                               ,'$this->r56_local' 
                               ,'$this->r56_dirarq' 
                               ,'$this->r56_posano' 
                               ,'$this->r56_posmes' 
                               ,'$this->r56_posreg' 
                               ,'$this->r56_poseve' 
                               ,'$this->r56_posq01' 
                               ,'$this->r56_vq01' 
                               ,'$this->r56_posq02' 
                               ,'$this->r56_vq02' 
                               ,'$this->r56_posq03' 
                               ,'$this->r56_vq03' 
                               ,$this->r56_linhastrailler 
                               ,$this->r56_linhasheader 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Definicao do convenio, local de leitura e toda a e ($this->r56_codrel."-".$this->r56_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Definicao do convenio, local de leitura e toda a e já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Definicao do convenio, local de leitura e toda a e ($this->r56_codrel."-".$this->r56_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r56_codrel."-".$this->r56_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r56_codrel,$this->r56_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3849,'$this->r56_codrel','I')");
       $resac = db_query("insert into db_acountkey values($acount,9893,'$this->r56_instit','I')");
       $resac = db_query("insert into db_acount values($acount,542,9893,'','".AddSlashes(pg_result($resaco,0,'r56_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3849,'','".AddSlashes(pg_result($resaco,0,'r56_codrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3861,'','".AddSlashes(pg_result($resaco,0,'r56_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3850,'','".AddSlashes(pg_result($resaco,0,'r56_local'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3862,'','".AddSlashes(pg_result($resaco,0,'r56_dirarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3851,'','".AddSlashes(pg_result($resaco,0,'r56_posano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3852,'','".AddSlashes(pg_result($resaco,0,'r56_posmes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3853,'','".AddSlashes(pg_result($resaco,0,'r56_posreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3854,'','".AddSlashes(pg_result($resaco,0,'r56_poseve'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3855,'','".AddSlashes(pg_result($resaco,0,'r56_posq01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3858,'','".AddSlashes(pg_result($resaco,0,'r56_vq01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3856,'','".AddSlashes(pg_result($resaco,0,'r56_posq02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3859,'','".AddSlashes(pg_result($resaco,0,'r56_vq02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3857,'','".AddSlashes(pg_result($resaco,0,'r56_posq03'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,3860,'','".AddSlashes(pg_result($resaco,0,'r56_vq03'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,9624,'','".AddSlashes(pg_result($resaco,0,'r56_linhastrailler'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,542,9623,'','".AddSlashes(pg_result($resaco,0,'r56_linhasheader'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r56_codrel=null,$r56_instit=null) { 
      $this->atualizacampos();
     $sql = " update convenio set ";
     $virgula = "";
     if(trim($this->r56_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_instit"])){ 
       $sql  .= $virgula." r56_instit = $this->r56_instit ";
       $virgula = ",";
       if(trim($this->r56_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "r56_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r56_codrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_codrel"])){ 
       $sql  .= $virgula." r56_codrel = '$this->r56_codrel' ";
       $virgula = ",";
       if(trim($this->r56_codrel) == null ){ 
         $this->erro_sql = " Campo convênio nao Informado.";
         $this->erro_campo = "r56_codrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r56_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_descr"])){ 
       $sql  .= $virgula." r56_descr = '$this->r56_descr' ";
       $virgula = ",";
       if(trim($this->r56_descr) == null ){ 
         $this->erro_sql = " Campo convênio nao Informado.";
         $this->erro_campo = "r56_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r56_local)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_local"])){ 
       $sql  .= $virgula." r56_local = '$this->r56_local' ";
       $virgula = ",";
       if(trim($this->r56_local) == null ){ 
         $this->erro_sql = " Campo leitura nao Informado.";
         $this->erro_campo = "r56_local";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r56_dirarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_dirarq"])){ 
       $sql  .= $virgula." r56_dirarq = '$this->r56_dirarq' ";
       $virgula = ",";
       if(trim($this->r56_dirarq) == null ){ 
         $this->erro_sql = " Campo Caminho nao Informado.";
         $this->erro_campo = "r56_dirarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r56_posano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_posano"])){ 
       $sql  .= $virgula." r56_posano = '$this->r56_posano' ";
       $virgula = ",";
     }
     if(trim($this->r56_posmes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_posmes"])){ 
       $sql  .= $virgula." r56_posmes = '$this->r56_posmes' ";
       $virgula = ",";
     }
     if(trim($this->r56_posreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_posreg"])){ 
       $sql  .= $virgula." r56_posreg = '$this->r56_posreg' ";
       $virgula = ",";
     }
     if(trim($this->r56_poseve)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_poseve"])){ 
       $sql  .= $virgula." r56_poseve = '$this->r56_poseve' ";
       $virgula = ",";
     }
     if(trim($this->r56_posq01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_posq01"])){ 
       $sql  .= $virgula." r56_posq01 = '$this->r56_posq01' ";
       $virgula = ",";
     }
     if(trim($this->r56_vq01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_vq01"])){ 
       $sql  .= $virgula." r56_vq01 = '$this->r56_vq01' ";
       $virgula = ",";
       if(trim($this->r56_vq01) == null ){ 
         $this->erro_sql = " Campo define se e valor ou quantidad nao Informado.";
         $this->erro_campo = "r56_vq01";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r56_posq02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_posq02"])){ 
       $sql  .= $virgula." r56_posq02 = '$this->r56_posq02' ";
       $virgula = ",";
     }
     if(trim($this->r56_vq02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_vq02"])){ 
       $sql  .= $virgula." r56_vq02 = '$this->r56_vq02' ";
       $virgula = ",";
       if(trim($this->r56_vq02) == null ){ 
         $this->erro_sql = " Campo def.se quant02 e valor ou quan nao Informado.";
         $this->erro_campo = "r56_vq02";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r56_posq03)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_posq03"])){ 
       $sql  .= $virgula." r56_posq03 = '$this->r56_posq03' ";
       $virgula = ",";
     }
     if(trim($this->r56_vq03)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_vq03"])){ 
       $sql  .= $virgula." r56_vq03 = '$this->r56_vq03' ";
       $virgula = ",";
       if(trim($this->r56_vq03) == null ){ 
         $this->erro_sql = " Campo def.se rubr.3 e valor ou qtd. nao Informado.";
         $this->erro_campo = "r56_vq03";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r56_linhastrailler)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_linhastrailler"])){ 
        if(trim($this->r56_linhastrailler)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r56_linhastrailler"])){ 
           $this->r56_linhastrailler = "0" ; 
        } 
       $sql  .= $virgula." r56_linhastrailler = $this->r56_linhastrailler ";
       $virgula = ",";
     }
     if(trim($this->r56_linhasheader)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r56_linhasheader"])){ 
        if(trim($this->r56_linhasheader)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r56_linhasheader"])){ 
           $this->r56_linhasheader = "0" ; 
        } 
       $sql  .= $virgula." r56_linhasheader = $this->r56_linhasheader ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($r56_codrel!=null){
       $sql .= " r56_codrel = '$this->r56_codrel'";
     }
     if($r56_instit!=null){
       $sql .= " and  r56_instit = $this->r56_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r56_codrel,$this->r56_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3849,'$this->r56_codrel','A')");
         $resac = db_query("insert into db_acountkey values($acount,9893,'$this->r56_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_instit"]))
           $resac = db_query("insert into db_acount values($acount,542,9893,'".AddSlashes(pg_result($resaco,$conresaco,'r56_instit'))."','$this->r56_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_codrel"]))
           $resac = db_query("insert into db_acount values($acount,542,3849,'".AddSlashes(pg_result($resaco,$conresaco,'r56_codrel'))."','$this->r56_codrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_descr"]))
           $resac = db_query("insert into db_acount values($acount,542,3861,'".AddSlashes(pg_result($resaco,$conresaco,'r56_descr'))."','$this->r56_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_local"]))
           $resac = db_query("insert into db_acount values($acount,542,3850,'".AddSlashes(pg_result($resaco,$conresaco,'r56_local'))."','$this->r56_local',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_dirarq"]))
           $resac = db_query("insert into db_acount values($acount,542,3862,'".AddSlashes(pg_result($resaco,$conresaco,'r56_dirarq'))."','$this->r56_dirarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_posano"]))
           $resac = db_query("insert into db_acount values($acount,542,3851,'".AddSlashes(pg_result($resaco,$conresaco,'r56_posano'))."','$this->r56_posano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_posmes"]))
           $resac = db_query("insert into db_acount values($acount,542,3852,'".AddSlashes(pg_result($resaco,$conresaco,'r56_posmes'))."','$this->r56_posmes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_posreg"]))
           $resac = db_query("insert into db_acount values($acount,542,3853,'".AddSlashes(pg_result($resaco,$conresaco,'r56_posreg'))."','$this->r56_posreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_poseve"]))
           $resac = db_query("insert into db_acount values($acount,542,3854,'".AddSlashes(pg_result($resaco,$conresaco,'r56_poseve'))."','$this->r56_poseve',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_posq01"]))
           $resac = db_query("insert into db_acount values($acount,542,3855,'".AddSlashes(pg_result($resaco,$conresaco,'r56_posq01'))."','$this->r56_posq01',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_vq01"]))
           $resac = db_query("insert into db_acount values($acount,542,3858,'".AddSlashes(pg_result($resaco,$conresaco,'r56_vq01'))."','$this->r56_vq01',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_posq02"]))
           $resac = db_query("insert into db_acount values($acount,542,3856,'".AddSlashes(pg_result($resaco,$conresaco,'r56_posq02'))."','$this->r56_posq02',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_vq02"]))
           $resac = db_query("insert into db_acount values($acount,542,3859,'".AddSlashes(pg_result($resaco,$conresaco,'r56_vq02'))."','$this->r56_vq02',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_posq03"]))
           $resac = db_query("insert into db_acount values($acount,542,3857,'".AddSlashes(pg_result($resaco,$conresaco,'r56_posq03'))."','$this->r56_posq03',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_vq03"]))
           $resac = db_query("insert into db_acount values($acount,542,3860,'".AddSlashes(pg_result($resaco,$conresaco,'r56_vq03'))."','$this->r56_vq03',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_linhastrailler"]))
           $resac = db_query("insert into db_acount values($acount,542,9624,'".AddSlashes(pg_result($resaco,$conresaco,'r56_linhastrailler'))."','$this->r56_linhastrailler',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r56_linhasheader"]))
           $resac = db_query("insert into db_acount values($acount,542,9623,'".AddSlashes(pg_result($resaco,$conresaco,'r56_linhasheader'))."','$this->r56_linhasheader',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Definicao do convenio, local de leitura e toda a e nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r56_codrel."-".$this->r56_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Definicao do convenio, local de leitura e toda a e nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r56_codrel."-".$this->r56_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r56_codrel."-".$this->r56_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r56_codrel=null,$r56_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r56_codrel,$r56_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3849,'$r56_codrel','E')");
         $resac = db_query("insert into db_acountkey values($acount,9893,'$r56_instit','E')");
         $resac = db_query("insert into db_acount values($acount,542,9893,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3849,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_codrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3861,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3850,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_local'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3862,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_dirarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3851,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_posano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3852,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_posmes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3853,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_posreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3854,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_poseve'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3855,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_posq01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3858,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_vq01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3856,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_posq02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3859,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_vq02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3857,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_posq03'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,3860,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_vq03'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,9624,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_linhastrailler'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,542,9623,'','".AddSlashes(pg_result($resaco,$iresaco,'r56_linhasheader'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from convenio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r56_codrel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r56_codrel = '$r56_codrel' ";
        }
        if($r56_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r56_instit = $r56_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Definicao do convenio, local de leitura e toda a e nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r56_codrel."-".$r56_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Definicao do convenio, local de leitura e toda a e nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r56_codrel."-".$r56_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r56_codrel."-".$r56_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:convenio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r56_codrel=null,$r56_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from convenio ";
     $sql .= "      inner join db_config  on  db_config.codigo = convenio.r56_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r56_codrel!=null ){
         $sql2 .= " where convenio.r56_codrel = '$r56_codrel' "; 
       } 
       if($r56_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " convenio.r56_instit = $r56_instit "; 
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
   function sql_query_file ( $r56_codrel=null,$r56_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from convenio ";
     $sql2 = "";
     if($dbwhere==""){
       if($r56_codrel!=null ){
         $sql2 .= " where convenio.r56_codrel = '$r56_codrel' "; 
       } 
       if($r56_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " convenio.r56_instit = $r56_instit "; 
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
   function sql_query_relac ( $r56_codrel=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from convenio ";
     $sql .= "      inner join relac on relac.r55_codeve = convenio.r56_codrel ";
     $sql2 = "";
     if($dbwhere==""){
       if($r56_codrel!=null ){
         $sql2 .= " where trim(convenio.r56_codrel) = '".trim($r56_codrel)."' ";
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