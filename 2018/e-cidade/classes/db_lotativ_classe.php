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
//CLASSE DA ENTIDADE lotativ
class cl_lotativ { 
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
   var $r41_anousu = 0; 
   var $r41_mesusu = 0; 
   var $r41_lotac = null; 
   var $r41_rubric = null; 
   var $r41_proati = null; 
   var $r41_painat = null; 
   var $r41_subele = null; 
   var $r41_rproat = null; 
   var $r41_rpaina = null; 
   var $r41_rsubel = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r41_anousu = int4 = Ano do Exercicio 
                 r41_mesusu = int4 = Mes do Exercicio 
                 r41_lotac = char(4) = Lotacao do Funcionario 
                 r41_rubric = char(     4) = Codigo da Rubrica 
                 r41_proati = char(     4) = Codigo do Projeto/Atividade 
                 r41_painat = char(     4) = Projeto/Atividade Inat./Pensio 
                 r41_subele = char(     6) = Sub-elemento para empenhos 
                 r41_rproat = char(     4) = proj/ativ ativo - reposicao 
                 r41_rpaina = char(     4) = proj/ativ inativos reposicao 
                 r41_rsubel = varchar(6) = Sub-ele. Rep. 
                 ";
   //funcao construtor da classe 
   function cl_lotativ() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lotativ"); 
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
       $this->r41_anousu = ($this->r41_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_anousu"]:$this->r41_anousu);
       $this->r41_mesusu = ($this->r41_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_mesusu"]:$this->r41_mesusu);
       $this->r41_lotac = ($this->r41_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_lotac"]:$this->r41_lotac);
       $this->r41_rubric = ($this->r41_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_rubric"]:$this->r41_rubric);
       $this->r41_proati = ($this->r41_proati == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_proati"]:$this->r41_proati);
       $this->r41_painat = ($this->r41_painat == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_painat"]:$this->r41_painat);
       $this->r41_subele = ($this->r41_subele == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_subele"]:$this->r41_subele);
       $this->r41_rproat = ($this->r41_rproat == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_rproat"]:$this->r41_rproat);
       $this->r41_rpaina = ($this->r41_rpaina == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_rpaina"]:$this->r41_rpaina);
       $this->r41_rsubel = ($this->r41_rsubel == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_rsubel"]:$this->r41_rsubel);
     }else{
       $this->r41_anousu = ($this->r41_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_anousu"]:$this->r41_anousu);
       $this->r41_mesusu = ($this->r41_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_mesusu"]:$this->r41_mesusu);
       $this->r41_lotac = ($this->r41_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_lotac"]:$this->r41_lotac);
       $this->r41_rubric = ($this->r41_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r41_rubric"]:$this->r41_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r41_anousu,$r41_mesusu,$r41_lotac,$r41_rubric){ 
      $this->atualizacampos();
     if($this->r41_proati == null ){ 
       $this->erro_sql = " Campo Codigo do Projeto/Atividade nao Informado.";
       $this->erro_campo = "r41_proati";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r41_painat == null ){ 
       $this->erro_sql = " Campo Projeto/Atividade Inat./Pensio nao Informado.";
       $this->erro_campo = "r41_painat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r41_subele == null ){ 
       $this->erro_sql = " Campo Sub-elemento para empenhos nao Informado.";
       $this->erro_campo = "r41_subele";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r41_rproat == null ){ 
       $this->erro_sql = " Campo proj/ativ ativo - reposicao nao Informado.";
       $this->erro_campo = "r41_rproat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r41_rpaina == null ){ 
       $this->erro_sql = " Campo proj/ativ inativos reposicao nao Informado.";
       $this->erro_campo = "r41_rpaina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r41_rsubel == null ){ 
       $this->erro_sql = " Campo Sub-ele. Rep. nao Informado.";
       $this->erro_campo = "r41_rsubel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r41_anousu = $r41_anousu; 
       $this->r41_mesusu = $r41_mesusu; 
       $this->r41_lotac = $r41_lotac; 
       $this->r41_rubric = $r41_rubric; 
     if(($this->r41_anousu == null) || ($this->r41_anousu == "") ){ 
       $this->erro_sql = " Campo r41_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r41_mesusu == null) || ($this->r41_mesusu == "") ){ 
       $this->erro_sql = " Campo r41_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r41_lotac == null) || ($this->r41_lotac == "") ){ 
       $this->erro_sql = " Campo r41_lotac nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r41_rubric == null) || ($this->r41_rubric == "") ){ 
       $this->erro_sql = " Campo r41_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lotativ(
                                       r41_anousu 
                                      ,r41_mesusu 
                                      ,r41_lotac 
                                      ,r41_rubric 
                                      ,r41_proati 
                                      ,r41_painat 
                                      ,r41_subele 
                                      ,r41_rproat 
                                      ,r41_rpaina 
                                      ,r41_rsubel 
                       )
                values (
                                $this->r41_anousu 
                               ,$this->r41_mesusu 
                               ,'$this->r41_lotac' 
                               ,'$this->r41_rubric' 
                               ,'$this->r41_proati' 
                               ,'$this->r41_painat' 
                               ,'$this->r41_subele' 
                               ,'$this->r41_rproat' 
                               ,'$this->r41_rpaina' 
                               ,'$this->r41_rsubel' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Remanegamento de verbas. ($this->r41_anousu."-".$this->r41_mesusu."-".$this->r41_lotac."-".$this->r41_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Remanegamento de verbas. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Remanegamento de verbas. ($this->r41_anousu."-".$this->r41_mesusu."-".$this->r41_lotac."-".$this->r41_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r41_anousu."-".$this->r41_mesusu."-".$this->r41_lotac."-".$this->r41_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r41_anousu,$this->r41_mesusu,$this->r41_lotac,$this->r41_rubric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4055,'$this->r41_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4056,'$this->r41_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4057,'$this->r41_lotac','I')");
       $resac = db_query("insert into db_acountkey values($acount,4058,'$this->r41_rubric','I')");
       $resac = db_query("insert into db_acount values($acount,565,4055,'','".AddSlashes(pg_result($resaco,0,'r41_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,565,4056,'','".AddSlashes(pg_result($resaco,0,'r41_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,565,4057,'','".AddSlashes(pg_result($resaco,0,'r41_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,565,4058,'','".AddSlashes(pg_result($resaco,0,'r41_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,565,4059,'','".AddSlashes(pg_result($resaco,0,'r41_proati'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,565,4060,'','".AddSlashes(pg_result($resaco,0,'r41_painat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,565,4061,'','".AddSlashes(pg_result($resaco,0,'r41_subele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,565,4062,'','".AddSlashes(pg_result($resaco,0,'r41_rproat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,565,4063,'','".AddSlashes(pg_result($resaco,0,'r41_rpaina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,565,4599,'','".AddSlashes(pg_result($resaco,0,'r41_rsubel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r41_anousu=null,$r41_mesusu=null,$r41_lotac=null,$r41_rubric=null) { 
      $this->atualizacampos();
     $sql = " update lotativ set ";
     $virgula = "";
     if(trim($this->r41_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r41_anousu"])){ 
       $sql  .= $virgula." r41_anousu = $this->r41_anousu ";
       $virgula = ",";
       if(trim($this->r41_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r41_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r41_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r41_mesusu"])){ 
       $sql  .= $virgula." r41_mesusu = $this->r41_mesusu ";
       $virgula = ",";
       if(trim($this->r41_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r41_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r41_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r41_lotac"])){ 
       $sql  .= $virgula." r41_lotac = '$this->r41_lotac' ";
       $virgula = ",";
       if(trim($this->r41_lotac) == null ){ 
         $this->erro_sql = " Campo Lotacao do Funcionario nao Informado.";
         $this->erro_campo = "r41_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r41_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r41_rubric"])){ 
       $sql  .= $virgula." r41_rubric = '$this->r41_rubric' ";
       $virgula = ",";
       if(trim($this->r41_rubric) == null ){ 
         $this->erro_sql = " Campo Codigo da Rubrica nao Informado.";
         $this->erro_campo = "r41_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r41_proati)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r41_proati"])){ 
       $sql  .= $virgula." r41_proati = '$this->r41_proati' ";
       $virgula = ",";
       if(trim($this->r41_proati) == null ){ 
         $this->erro_sql = " Campo Codigo do Projeto/Atividade nao Informado.";
         $this->erro_campo = "r41_proati";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r41_painat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r41_painat"])){ 
       $sql  .= $virgula." r41_painat = '$this->r41_painat' ";
       $virgula = ",";
       if(trim($this->r41_painat) == null ){ 
         $this->erro_sql = " Campo Projeto/Atividade Inat./Pensio nao Informado.";
         $this->erro_campo = "r41_painat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r41_subele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r41_subele"])){ 
       $sql  .= $virgula." r41_subele = '$this->r41_subele' ";
       $virgula = ",";
       if(trim($this->r41_subele) == null ){ 
         $this->erro_sql = " Campo Sub-elemento para empenhos nao Informado.";
         $this->erro_campo = "r41_subele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r41_rproat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r41_rproat"])){ 
       $sql  .= $virgula." r41_rproat = '$this->r41_rproat' ";
       $virgula = ",";
       if(trim($this->r41_rproat) == null ){ 
         $this->erro_sql = " Campo proj/ativ ativo - reposicao nao Informado.";
         $this->erro_campo = "r41_rproat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r41_rpaina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r41_rpaina"])){ 
       $sql  .= $virgula." r41_rpaina = '$this->r41_rpaina' ";
       $virgula = ",";
       if(trim($this->r41_rpaina) == null ){ 
         $this->erro_sql = " Campo proj/ativ inativos reposicao nao Informado.";
         $this->erro_campo = "r41_rpaina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r41_rsubel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r41_rsubel"])){ 
       $sql  .= $virgula." r41_rsubel = '$this->r41_rsubel' ";
       $virgula = ",";
       if(trim($this->r41_rsubel) == null ){ 
         $this->erro_sql = " Campo Sub-ele. Rep. nao Informado.";
         $this->erro_campo = "r41_rsubel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r41_anousu!=null){
       $sql .= " r41_anousu = $this->r41_anousu";
     }
     if($r41_mesusu!=null){
       $sql .= " and  r41_mesusu = $this->r41_mesusu";
     }
     if($r41_lotac!=null){
       $sql .= " and  r41_lotac = '$this->r41_lotac'";
     }
     if($r41_rubric!=null){
       $sql .= " and  r41_rubric = '$this->r41_rubric'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r41_anousu,$this->r41_mesusu,$this->r41_lotac,$this->r41_rubric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4055,'$this->r41_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4056,'$this->r41_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4057,'$this->r41_lotac','A')");
         $resac = db_query("insert into db_acountkey values($acount,4058,'$this->r41_rubric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r41_anousu"]))
           $resac = db_query("insert into db_acount values($acount,565,4055,'".AddSlashes(pg_result($resaco,$conresaco,'r41_anousu'))."','$this->r41_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r41_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,565,4056,'".AddSlashes(pg_result($resaco,$conresaco,'r41_mesusu'))."','$this->r41_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r41_lotac"]))
           $resac = db_query("insert into db_acount values($acount,565,4057,'".AddSlashes(pg_result($resaco,$conresaco,'r41_lotac'))."','$this->r41_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r41_rubric"]))
           $resac = db_query("insert into db_acount values($acount,565,4058,'".AddSlashes(pg_result($resaco,$conresaco,'r41_rubric'))."','$this->r41_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r41_proati"]))
           $resac = db_query("insert into db_acount values($acount,565,4059,'".AddSlashes(pg_result($resaco,$conresaco,'r41_proati'))."','$this->r41_proati',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r41_painat"]))
           $resac = db_query("insert into db_acount values($acount,565,4060,'".AddSlashes(pg_result($resaco,$conresaco,'r41_painat'))."','$this->r41_painat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r41_subele"]))
           $resac = db_query("insert into db_acount values($acount,565,4061,'".AddSlashes(pg_result($resaco,$conresaco,'r41_subele'))."','$this->r41_subele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r41_rproat"]))
           $resac = db_query("insert into db_acount values($acount,565,4062,'".AddSlashes(pg_result($resaco,$conresaco,'r41_rproat'))."','$this->r41_rproat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r41_rpaina"]))
           $resac = db_query("insert into db_acount values($acount,565,4063,'".AddSlashes(pg_result($resaco,$conresaco,'r41_rpaina'))."','$this->r41_rpaina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r41_rsubel"]))
           $resac = db_query("insert into db_acount values($acount,565,4599,'".AddSlashes(pg_result($resaco,$conresaco,'r41_rsubel'))."','$this->r41_rsubel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Remanegamento de verbas. nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r41_anousu."-".$this->r41_mesusu."-".$this->r41_lotac."-".$this->r41_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Remanegamento de verbas. nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r41_anousu."-".$this->r41_mesusu."-".$this->r41_lotac."-".$this->r41_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r41_anousu."-".$this->r41_mesusu."-".$this->r41_lotac."-".$this->r41_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r41_anousu=null,$r41_mesusu=null,$r41_lotac=null,$r41_rubric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r41_anousu,$r41_mesusu,$r41_lotac,$r41_rubric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4055,'$r41_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4056,'$r41_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4057,'$r41_lotac','E')");
         $resac = db_query("insert into db_acountkey values($acount,4058,'$r41_rubric','E')");
         $resac = db_query("insert into db_acount values($acount,565,4055,'','".AddSlashes(pg_result($resaco,$iresaco,'r41_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,565,4056,'','".AddSlashes(pg_result($resaco,$iresaco,'r41_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,565,4057,'','".AddSlashes(pg_result($resaco,$iresaco,'r41_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,565,4058,'','".AddSlashes(pg_result($resaco,$iresaco,'r41_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,565,4059,'','".AddSlashes(pg_result($resaco,$iresaco,'r41_proati'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,565,4060,'','".AddSlashes(pg_result($resaco,$iresaco,'r41_painat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,565,4061,'','".AddSlashes(pg_result($resaco,$iresaco,'r41_subele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,565,4062,'','".AddSlashes(pg_result($resaco,$iresaco,'r41_rproat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,565,4063,'','".AddSlashes(pg_result($resaco,$iresaco,'r41_rpaina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,565,4599,'','".AddSlashes(pg_result($resaco,$iresaco,'r41_rsubel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lotativ
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r41_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r41_anousu = $r41_anousu ";
        }
        if($r41_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r41_mesusu = $r41_mesusu ";
        }
        if($r41_lotac != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r41_lotac = '$r41_lotac' ";
        }
        if($r41_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r41_rubric = '$r41_rubric' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Remanegamento de verbas. nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r41_anousu."-".$r41_mesusu."-".$r41_lotac."-".$r41_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Remanegamento de verbas. nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r41_anousu."-".$r41_mesusu."-".$r41_lotac."-".$r41_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r41_anousu."-".$r41_mesusu."-".$r41_lotac."-".$r41_rubric;
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
        $this->erro_sql   = "Record Vazio na Tabela:lotativ";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r41_anousu,$this->r41_mesusu,$this->r41_rubric);
   }
   function sql_query ( $r41_anousu=null,$r41_mesusu=null,$r41_lotac=null,$r41_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lotativ ";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = lotativ.r41_anousu 
		                                   and  lotacao.r13_mesusu = lotativ.r41_mesusu 
																			 and  lotacao.r13_codigo = lotativ.r41_lotac
																			 and  lotacao.r13_instit = ".db_getsession("DB_instit")." ";																			 ;
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = lotativ.r41_anousu 
		                                    and  rubricas.r06_mesusu = lotativ.r41_mesusu 
																				and  rubricas.r06_codigo = lotativ.r41_rubric
																				and  rubricas.r06_instit = lotacao.r13_instit ";
     $sql .= "      inner join db_config  on  db_config.codigo = lotacao.r13_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($r41_anousu!=null ){
         $sql2 .= " where lotativ.r41_anousu = $r41_anousu "; 
       } 
       if($r41_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotativ.r41_mesusu = $r41_mesusu "; 
       } 
       if($r41_lotac!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotativ.r41_lotac = '$r41_lotac' "; 
       } 
       if($r41_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotativ.r41_rubric = '$r41_rubric' "; 
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
   function sql_query_file ( $r41_anousu=null,$r41_mesusu=null,$r41_lotac=null,$r41_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lotativ ";
     $sql2 = "";
     if($dbwhere==""){
       if($r41_anousu!=null ){
         $sql2 .= " where lotativ.r41_anousu = $r41_anousu "; 
       } 
       if($r41_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotativ.r41_mesusu = $r41_mesusu "; 
       } 
       if($r41_lotac!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotativ.r41_lotac = '$r41_lotac' "; 
       } 
       if($r41_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " lotativ.r41_rubric = '$r41_rubric' "; 
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