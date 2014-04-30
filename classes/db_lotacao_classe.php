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
//CLASSE DA ENTIDADE lotacao
class cl_lotacao { 
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
   var $r13_anousu = 0; 
   var $r13_mesusu = 0; 
   var $r13_codigo = null; 
   var $r13_descr = null; 
   var $r13_reduz = 0; 
   var $r13_proati = null; 
   var $r13_painat = null; 
   var $r13_descro = null; 
   var $r13_descru = null; 
   var $r13_subele = null; 
   var $r13_calend = null; 
   var $r13_rproat = null; 
   var $r13_rpaina = null; 
   var $r13_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r13_anousu = int4 = Ano do Exercicio 
                 r13_mesusu = int4 = Mes do Exercicio 
                 r13_codigo = char(4) = Código 
                 r13_descr = varchar(40) = Descrição 
                 r13_reduz = int4 = Codigo Reduzido da Dotacao 
                 r13_proati = varchar(4) = Projeto/Atividade 
                 r13_painat = varchar(4) = Projeto/Atividade Inat./Pensio 
                 r13_descro = varchar(40) = Órgão 
                 r13_descru = varchar(40) = Unidade 
                 r13_subele = varchar(6) = Sub-elemento para empenhos 
                 r13_calend = varchar(2) = calendário 
                 r13_rproat = varchar(4) = proj/ativ ativo - reposicao 
                 r13_rpaina = varchar(4) = proj/ativ inativos reposicao 
                 r13_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_lotacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lotacao"); 
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
       $this->r13_anousu = ($this->r13_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_anousu"]:$this->r13_anousu);
       $this->r13_mesusu = ($this->r13_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_mesusu"]:$this->r13_mesusu);
       $this->r13_codigo = ($this->r13_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_codigo"]:$this->r13_codigo);
       $this->r13_descr = ($this->r13_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_descr"]:$this->r13_descr);
       $this->r13_reduz = ($this->r13_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_reduz"]:$this->r13_reduz);
       $this->r13_proati = ($this->r13_proati == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_proati"]:$this->r13_proati);
       $this->r13_painat = ($this->r13_painat == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_painat"]:$this->r13_painat);
       $this->r13_descro = ($this->r13_descro == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_descro"]:$this->r13_descro);
       $this->r13_descru = ($this->r13_descru == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_descru"]:$this->r13_descru);
       $this->r13_subele = ($this->r13_subele == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_subele"]:$this->r13_subele);
       $this->r13_calend = ($this->r13_calend == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_calend"]:$this->r13_calend);
       $this->r13_rproat = ($this->r13_rproat == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_rproat"]:$this->r13_rproat);
       $this->r13_rpaina = ($this->r13_rpaina == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_rpaina"]:$this->r13_rpaina);
       $this->r13_instit = ($this->r13_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_instit"]:$this->r13_instit);
     }else{
       $this->r13_anousu = ($this->r13_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_anousu"]:$this->r13_anousu);
       $this->r13_mesusu = ($this->r13_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_mesusu"]:$this->r13_mesusu);
       $this->r13_codigo = ($this->r13_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r13_codigo"]:$this->r13_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($r13_anousu,$r13_mesusu,$r13_codigo){ 
      $this->atualizacampos();
     if($this->r13_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "r13_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r13_reduz == null ){ 
       $this->erro_sql = " Campo Codigo Reduzido da Dotacao nao Informado.";
       $this->erro_campo = "r13_reduz";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r13_proati == null ){ 
       $this->erro_sql = " Campo Projeto/Atividade nao Informado.";
       $this->erro_campo = "r13_proati";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r13_painat == null ){ 
       $this->erro_sql = " Campo Projeto/Atividade Inat./Pensio nao Informado.";
       $this->erro_campo = "r13_painat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r13_descro == null ){ 
       $this->erro_sql = " Campo Órgão nao Informado.";
       $this->erro_campo = "r13_descro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r13_descru == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "r13_descru";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r13_subele == null ){ 
       $this->erro_sql = " Campo Sub-elemento para empenhos nao Informado.";
       $this->erro_campo = "r13_subele";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r13_calend == null ){ 
       $this->erro_sql = " Campo calendário nao Informado.";
       $this->erro_campo = "r13_calend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r13_rproat == null ){ 
       $this->erro_sql = " Campo proj/ativ ativo - reposicao nao Informado.";
       $this->erro_campo = "r13_rproat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r13_rpaina == null ){ 
       $this->erro_sql = " Campo proj/ativ inativos reposicao nao Informado.";
       $this->erro_campo = "r13_rpaina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r13_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r13_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r13_anousu = $r13_anousu; 
       $this->r13_mesusu = $r13_mesusu; 
       $this->r13_codigo = $r13_codigo; 
     if(($this->r13_anousu == null) || ($this->r13_anousu == "") ){ 
       $this->erro_sql = " Campo r13_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r13_mesusu == null) || ($this->r13_mesusu == "") ){ 
       $this->erro_sql = " Campo r13_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r13_codigo == null) || ($this->r13_codigo == "") ){ 
       $this->erro_sql = " Campo r13_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lotacao(
                                       r13_anousu 
                                      ,r13_mesusu 
                                      ,r13_codigo 
                                      ,r13_descr 
                                      ,r13_reduz 
                                      ,r13_proati 
                                      ,r13_painat 
                                      ,r13_descro 
                                      ,r13_descru 
                                      ,r13_subele 
                                      ,r13_calend 
                                      ,r13_rproat 
                                      ,r13_rpaina 
                                      ,r13_instit 
                       )
                values (
                                $this->r13_anousu 
                               ,$this->r13_mesusu 
                               ,'$this->r13_codigo' 
                               ,'$this->r13_descr' 
                               ,$this->r13_reduz 
                               ,'$this->r13_proati' 
                               ,'$this->r13_painat' 
                               ,'$this->r13_descro' 
                               ,'$this->r13_descru' 
                               ,'$this->r13_subele' 
                               ,'$this->r13_calend' 
                               ,'$this->r13_rproat' 
                               ,'$this->r13_rpaina' 
                               ,$this->r13_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lotacao ($this->r13_anousu."-".$this->r13_mesusu."-".$this->r13_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lotacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lotacao ($this->r13_anousu."-".$this->r13_mesusu."-".$this->r13_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r13_anousu."-".$this->r13_mesusu."-".$this->r13_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r13_anousu,$this->r13_mesusu,$this->r13_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4042,'$this->r13_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4043,'$this->r13_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4044,'$this->r13_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,564,4042,'','".AddSlashes(pg_result($resaco,0,'r13_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,4043,'','".AddSlashes(pg_result($resaco,0,'r13_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,4044,'','".AddSlashes(pg_result($resaco,0,'r13_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,4045,'','".AddSlashes(pg_result($resaco,0,'r13_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,4046,'','".AddSlashes(pg_result($resaco,0,'r13_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,4047,'','".AddSlashes(pg_result($resaco,0,'r13_proati'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,4048,'','".AddSlashes(pg_result($resaco,0,'r13_painat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,4049,'','".AddSlashes(pg_result($resaco,0,'r13_descro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,4050,'','".AddSlashes(pg_result($resaco,0,'r13_descru'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,4051,'','".AddSlashes(pg_result($resaco,0,'r13_subele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,4052,'','".AddSlashes(pg_result($resaco,0,'r13_calend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,4053,'','".AddSlashes(pg_result($resaco,0,'r13_rproat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,4054,'','".AddSlashes(pg_result($resaco,0,'r13_rpaina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,564,7634,'','".AddSlashes(pg_result($resaco,0,'r13_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r13_anousu=null,$r13_mesusu=null,$r13_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lotacao set ";
     $virgula = "";
     if(trim($this->r13_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_anousu"])){ 
       $sql  .= $virgula." r13_anousu = $this->r13_anousu ";
       $virgula = ",";
       if(trim($this->r13_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r13_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_mesusu"])){ 
       $sql  .= $virgula." r13_mesusu = $this->r13_mesusu ";
       $virgula = ",";
       if(trim($this->r13_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r13_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_codigo"])){ 
       $sql  .= $virgula." r13_codigo = '$this->r13_codigo' ";
       $virgula = ",";
       if(trim($this->r13_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "r13_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_descr"])){ 
       $sql  .= $virgula." r13_descr = '$this->r13_descr' ";
       $virgula = ",";
       if(trim($this->r13_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "r13_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_reduz"])){ 
       $sql  .= $virgula." r13_reduz = $this->r13_reduz ";
       $virgula = ",";
       if(trim($this->r13_reduz) == null ){ 
         $this->erro_sql = " Campo Codigo Reduzido da Dotacao nao Informado.";
         $this->erro_campo = "r13_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_proati)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_proati"])){ 
       $sql  .= $virgula." r13_proati = '$this->r13_proati' ";
       $virgula = ",";
       if(trim($this->r13_proati) == null ){ 
         $this->erro_sql = " Campo Projeto/Atividade nao Informado.";
         $this->erro_campo = "r13_proati";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_painat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_painat"])){ 
       $sql  .= $virgula." r13_painat = '$this->r13_painat' ";
       $virgula = ",";
       if(trim($this->r13_painat) == null ){ 
         $this->erro_sql = " Campo Projeto/Atividade Inat./Pensio nao Informado.";
         $this->erro_campo = "r13_painat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_descro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_descro"])){ 
       $sql  .= $virgula." r13_descro = '$this->r13_descro' ";
       $virgula = ",";
       if(trim($this->r13_descro) == null ){ 
         $this->erro_sql = " Campo Órgão nao Informado.";
         $this->erro_campo = "r13_descro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_descru)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_descru"])){ 
       $sql  .= $virgula." r13_descru = '$this->r13_descru' ";
       $virgula = ",";
       if(trim($this->r13_descru) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "r13_descru";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_subele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_subele"])){ 
       $sql  .= $virgula." r13_subele = '$this->r13_subele' ";
       $virgula = ",";
       if(trim($this->r13_subele) == null ){ 
         $this->erro_sql = " Campo Sub-elemento para empenhos nao Informado.";
         $this->erro_campo = "r13_subele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_calend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_calend"])){ 
       $sql  .= $virgula." r13_calend = '$this->r13_calend' ";
       $virgula = ",";
       if(trim($this->r13_calend) == null ){ 
         $this->erro_sql = " Campo calendário nao Informado.";
         $this->erro_campo = "r13_calend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_rproat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_rproat"])){ 
       $sql  .= $virgula." r13_rproat = '$this->r13_rproat' ";
       $virgula = ",";
       if(trim($this->r13_rproat) == null ){ 
         $this->erro_sql = " Campo proj/ativ ativo - reposicao nao Informado.";
         $this->erro_campo = "r13_rproat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_rpaina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_rpaina"])){ 
       $sql  .= $virgula." r13_rpaina = '$this->r13_rpaina' ";
       $virgula = ",";
       if(trim($this->r13_rpaina) == null ){ 
         $this->erro_sql = " Campo proj/ativ inativos reposicao nao Informado.";
         $this->erro_campo = "r13_rpaina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r13_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r13_instit"])){ 
       $sql  .= $virgula." r13_instit = $this->r13_instit ";
       $virgula = ",";
       if(trim($this->r13_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r13_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r13_anousu!=null){
       $sql .= " r13_anousu = $this->r13_anousu";
     }
     if($r13_mesusu!=null){
       $sql .= " and  r13_mesusu = $this->r13_mesusu";
     }
     if($r13_codigo!=null){
       $sql .= " and  r13_codigo = '$this->r13_codigo'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r13_anousu,$this->r13_mesusu,$this->r13_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4042,'$this->r13_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4043,'$this->r13_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4044,'$this->r13_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_anousu"]))
           $resac = db_query("insert into db_acount values($acount,564,4042,'".AddSlashes(pg_result($resaco,$conresaco,'r13_anousu'))."','$this->r13_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,564,4043,'".AddSlashes(pg_result($resaco,$conresaco,'r13_mesusu'))."','$this->r13_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_codigo"]))
           $resac = db_query("insert into db_acount values($acount,564,4044,'".AddSlashes(pg_result($resaco,$conresaco,'r13_codigo'))."','$this->r13_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_descr"]))
           $resac = db_query("insert into db_acount values($acount,564,4045,'".AddSlashes(pg_result($resaco,$conresaco,'r13_descr'))."','$this->r13_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_reduz"]))
           $resac = db_query("insert into db_acount values($acount,564,4046,'".AddSlashes(pg_result($resaco,$conresaco,'r13_reduz'))."','$this->r13_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_proati"]))
           $resac = db_query("insert into db_acount values($acount,564,4047,'".AddSlashes(pg_result($resaco,$conresaco,'r13_proati'))."','$this->r13_proati',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_painat"]))
           $resac = db_query("insert into db_acount values($acount,564,4048,'".AddSlashes(pg_result($resaco,$conresaco,'r13_painat'))."','$this->r13_painat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_descro"]))
           $resac = db_query("insert into db_acount values($acount,564,4049,'".AddSlashes(pg_result($resaco,$conresaco,'r13_descro'))."','$this->r13_descro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_descru"]))
           $resac = db_query("insert into db_acount values($acount,564,4050,'".AddSlashes(pg_result($resaco,$conresaco,'r13_descru'))."','$this->r13_descru',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_subele"]))
           $resac = db_query("insert into db_acount values($acount,564,4051,'".AddSlashes(pg_result($resaco,$conresaco,'r13_subele'))."','$this->r13_subele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_calend"]))
           $resac = db_query("insert into db_acount values($acount,564,4052,'".AddSlashes(pg_result($resaco,$conresaco,'r13_calend'))."','$this->r13_calend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_rproat"]))
           $resac = db_query("insert into db_acount values($acount,564,4053,'".AddSlashes(pg_result($resaco,$conresaco,'r13_rproat'))."','$this->r13_rproat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_rpaina"]))
           $resac = db_query("insert into db_acount values($acount,564,4054,'".AddSlashes(pg_result($resaco,$conresaco,'r13_rpaina'))."','$this->r13_rpaina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r13_instit"]))
           $resac = db_query("insert into db_acount values($acount,564,7634,'".AddSlashes(pg_result($resaco,$conresaco,'r13_instit'))."','$this->r13_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lotacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r13_anousu."-".$this->r13_mesusu."-".$this->r13_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lotacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r13_anousu."-".$this->r13_mesusu."-".$this->r13_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r13_anousu."-".$this->r13_mesusu."-".$this->r13_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r13_anousu=null,$r13_mesusu=null,$r13_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r13_anousu,$r13_mesusu,$r13_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4042,'$r13_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4043,'$r13_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4044,'$r13_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,564,4042,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,4043,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,4044,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,4045,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,4046,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,4047,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_proati'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,4048,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_painat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,4049,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_descro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,4050,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_descru'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,4051,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_subele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,4052,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_calend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,4053,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_rproat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,4054,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_rpaina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,564,7634,'','".AddSlashes(pg_result($resaco,$iresaco,'r13_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lotacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r13_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r13_anousu = $r13_anousu ";
        }
        if($r13_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r13_mesusu = $r13_mesusu ";
        }
        if($r13_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r13_codigo = '$r13_codigo' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lotacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r13_anousu."-".$r13_mesusu."-".$r13_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lotacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r13_anousu."-".$r13_mesusu."-".$r13_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r13_anousu."-".$r13_mesusu."-".$r13_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lotacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r13_anousu=null,$r13_mesusu=null,$r13_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lotacao ";
     $sql .= "      inner join db_config  on  db_config.codigo = lotacao.r13_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($r13_anousu!=null ){
         $sql2 .= " where lotacao.r13_anousu = $r13_anousu "; 
       } 
       if($r13_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotacao.r13_mesusu = $r13_mesusu "; 
       } 
       if($r13_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotacao.r13_codigo = '$r13_codigo' "; 
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
   function sql_query_cgm ( $r13_anousu=null,$r13_mesusu=null,$r13_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lotacao ";
     $sql .= "      inner join pessoal  on pessoal.r01_anousu = lotacao.r13_anousu 
		                                   and pessoal.r01_mesusu = lotacao.r13_mesusu 
																			 and pessoal.r01_lotac  = lotacao.r13_codigo
																			 and pessoal.r01_instit = lotacao.r13_instit ";
     $sql .= "      inner join cgm      on       cgm.z01_numcgm = pessoal.r01_numcgm ";
     $sql .= "      inner join rhfuncao on rhfuncao.rh37_funcao = pessoal.r01_funcao ";
     $sql2 = "";
     if($dbwhere==""){
       if($r13_anousu!=null ){
         $sql2 .= " where lotacao.r13_anousu = $r13_anousu "; 
       } 
       if($r13_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotacao.r13_mesusu = $r13_mesusu "; 
       } 
       if($r13_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotacao.r13_codigo = '$r13_codigo' "; 
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
   function sql_query_file ( $r13_anousu=null,$r13_mesusu=null,$r13_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lotacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($r13_anousu!=null ){
         $sql2 .= " where lotacao.r13_anousu = $r13_anousu "; 
       } 
       if($r13_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotacao.r13_mesusu = $r13_mesusu "; 
       } 
       if($r13_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotacao.r13_codigo = '$r13_codigo' "; 
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
   function sql_query_orgao ( $r13_anousu=null,$r13_mesusu=null,$r13_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lotacao ";
     $sql .= "      inner join rhlotaexe  on  rhlotaexe.rh26_codigo = rhlota.r70_codigo ";
     $sql .= "      inner join orcorgao   on  orcorgao.o40_orgao = rhlotaexe.rh26_orgao 
		                                     and orcorgao.o40_anousu = rhlotaexe.rh26_anousu
																				 and orcorgao.o40_instit = rhlotaexe.rh26_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($r13_anousu!=null ){
         $sql2 .= " where lotacao.r13_anousu = $r13_anousu "; 
       } 
       if($r13_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotacao.r13_mesusu = $r13_mesusu "; 
       } 
       if($r13_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotacao.r13_codigo = '$r13_codigo' "; 
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