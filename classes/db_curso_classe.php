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

//MODULO: protocolo
//CLASSE DA ENTIDADE curso
class cl_curso { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $s_codcurso = 0; 
   var $s_nomecurso = null; 
   var $s_dtcurso_dia = null; 
   var $s_dtcurso_mes = null; 
   var $s_dtcurso_ano = null; 
   var $s_dtcurso = null; 
   var $s_vagcurso = 0; 
   var $s_obscurso = null; 
   var $z01_numcgm = 0; 
   var $s_obscur = null; 
   var $s_sncurso = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s_codcurso = int8 = Codigo do Curso 
                 s_nomecurso = varchar(40) = Nome do Curso 
                 s_dtcurso = date = Data criacao 
                 s_vagcurso = int8 = Vagas 
                 s_obscurso = varchar(80) = Observações 
                 z01_numcgm = int4 = Numcgm 
                 s_obscur = text = Observacoes text 
                 s_sncurso = bool = Ativo 
                 ";
   //funcao construtor da classe 
   function cl_curso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("curso"); 
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
       $this->s_codcurso = ($this->s_codcurso == ""?@$GLOBALS["HTTP_POST_VARS"]["s_codcurso"]:$this->s_codcurso);
       $this->s_nomecurso = ($this->s_nomecurso == ""?@$GLOBALS["HTTP_POST_VARS"]["s_nomecurso"]:$this->s_nomecurso);
       if($this->s_dtcurso == ""){
         $this->s_dtcurso_dia = @$GLOBALS["HTTP_POST_VARS"]["s_dtcurso_dia"];
         $this->s_dtcurso_mes = @$GLOBALS["HTTP_POST_VARS"]["s_dtcurso_mes"];
         $this->s_dtcurso_ano = @$GLOBALS["HTTP_POST_VARS"]["s_dtcurso_ano"];
         if($this->s_dtcurso_dia != ""){
            $this->s_dtcurso = $this->s_dtcurso_ano."-".$this->s_dtcurso_mes."-".$this->s_dtcurso_dia;
         }
       }
       $this->s_vagcurso = ($this->s_vagcurso == ""?@$GLOBALS["HTTP_POST_VARS"]["s_vagcurso"]:$this->s_vagcurso);
       $this->s_obscurso = ($this->s_obscurso == ""?@$GLOBALS["HTTP_POST_VARS"]["s_obscurso"]:$this->s_obscurso);
       $this->z01_numcgm = ($this->z01_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_numcgm"]:$this->z01_numcgm);
       $this->s_obscur = ($this->s_obscur == ""?@$GLOBALS["HTTP_POST_VARS"]["s_obscur"]:$this->s_obscur);
       $this->s_sncurso = ($this->s_sncurso == "f"?@$GLOBALS["HTTP_POST_VARS"]["s_sncurso"]:$this->s_sncurso);
     }else{
       $this->s_codcurso = ($this->s_codcurso == ""?@$GLOBALS["HTTP_POST_VARS"]["s_codcurso"]:$this->s_codcurso);
     }
   }
   // funcao para inclusao
   function incluir ($s_codcurso){ 
      $this->atualizacampos();
     if($this->s_nomecurso == null ){ 
       $this->erro_sql = " Campo Nome do Curso nao Informado.";
       $this->erro_campo = "s_nomecurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_dtcurso == null ){ 
       $this->erro_sql = " Campo Data criacao nao Informado.";
       $this->erro_campo = "s_dtcurso_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_vagcurso == null ){ 
       $this->erro_sql = " Campo Vagas nao Informado.";
       $this->erro_campo = "s_vagcurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_obscurso == null ){ 
       $this->erro_sql = " Campo Observações nao Informado.";
       $this->erro_campo = "s_obscurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "z01_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_obscur == null ){ 
       $this->erro_sql = " Campo Observacoes text nao Informado.";
       $this->erro_campo = "s_obscur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s_sncurso == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "s_sncurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->s_codcurso = $s_codcurso; 
     if(($this->s_codcurso == null) || ($this->s_codcurso == "") ){ 
       $this->erro_sql = " Campo s_codcurso nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into curso(
                                       s_codcurso 
                                      ,s_nomecurso 
                                      ,s_dtcurso 
                                      ,s_vagcurso 
                                      ,s_obscurso 
                                      ,z01_numcgm 
                                      ,s_obscur 
                                      ,s_sncurso 
                       )
                values (
                                $this->s_codcurso 
                               ,'$this->s_nomecurso' 
                               ,".($this->s_dtcurso == "null" || $this->s_dtcurso == ""?"null":"'".$this->s_dtcurso."'")." 
                               ,$this->s_vagcurso 
                               ,'$this->s_obscurso' 
                               ,$this->z01_numcgm 
                               ,'$this->s_obscur' 
                               ,'$this->s_sncurso' 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Curso de PHP ($this->s_codcurso) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Curso de PHP já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Curso de PHP ($this->s_codcurso) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s_codcurso;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->s_codcurso));
     $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
     $acount = pg_result($resac,0,0);
     $resac = pg_query("insert into db_acountkey values($acount,2411,'$this->s_codcurso','I')");
     $resac = pg_query("insert into db_acount values($acount,391,2411,'','".pg_result($resaco,0,'s_codcurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,2412,'','".pg_result($resaco,0,'s_nomecurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,2413,'','".pg_result($resaco,0,'s_dtcurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,2414,'','".pg_result($resaco,0,'s_vagcurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,2415,'','".pg_result($resaco,0,'s_obscurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,216,'','".pg_result($resaco,0,'z01_numcgm')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,2416,'','".pg_result($resaco,0,'s_obscur')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,2417,'','".pg_result($resaco,0,'s_sncurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     pg_free_result($resaco);
     return true;
   } 
   // funcao para alteracao
   function alterar ($s_codcurso=null) { 
      $this->atualizacampos();
     $sql = " update curso set ";
     $virgula = "";
     if(isset($GLOBALS["HTTP_POST_VARS"]["s_codcurso"])){ 
       $sql  .= $virgula." s_codcurso = $this->s_codcurso ";
       $virgula = ",";
       if($this->s_codcurso == null ){ 
         $this->erro_sql = " Campo Codigo do Curso nao Informado.";
         $this->erro_campo = "s_codcurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["s_nomecurso"])){ 
       $sql  .= $virgula." s_nomecurso = '$this->s_nomecurso' ";
       $virgula = ",";
       if($this->s_nomecurso == null ){ 
         $this->erro_sql = " Campo Nome do Curso nao Informado.";
         $this->erro_campo = "s_nomecurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["s_dtcurso_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s_dtcurso_dia"] !="") ){ 
       $sql  .= $virgula." s_dtcurso = '$this->s_dtcurso' ";
       $virgula = ",";
       if($this->s_dtcurso == null ){ 
         $this->erro_sql = " Campo Data criacao nao Informado.";
         $this->erro_campo = "s_dtcurso_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       $sql  .= $virgula." s_dtcurso = null ";
       $virgula = ",";
       if($this->s_dtcurso == null ){ 
         $this->erro_sql = " Campo Data criacao nao Informado.";
         $this->erro_campo = "s_dtcurso_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["s_vagcurso"])){ 
       $sql  .= $virgula." s_vagcurso = $this->s_vagcurso ";
       $virgula = ",";
       if($this->s_vagcurso == null ){ 
         $this->erro_sql = " Campo Vagas nao Informado.";
         $this->erro_campo = "s_vagcurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["s_obscurso"])){ 
       $sql  .= $virgula." s_obscurso = '$this->s_obscurso' ";
       $virgula = ",";
       if($this->s_obscurso == null ){ 
         $this->erro_sql = " Campo Observações nao Informado.";
         $this->erro_campo = "s_obscurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["z01_numcgm"])){ 
       $sql  .= $virgula." z01_numcgm = $this->z01_numcgm ";
       $virgula = ",";
       if($this->z01_numcgm == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "z01_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["s_obscur"])){ 
       $sql  .= $virgula." s_obscur = '$this->s_obscur' ";
       $virgula = ",";
       if($this->s_obscur == null ){ 
         $this->erro_sql = " Campo Observacoes text nao Informado.";
         $this->erro_campo = "s_obscur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["s_sncurso"])){ 
       $sql  .= $virgula." s_sncurso = '$this->s_sncurso' ";
       $virgula = ",";
       if($this->s_sncurso == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "s_sncurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  s_codcurso = $this->s_codcurso
";
     $resaco = $this->sql_record($this->sql_query_file($this->s_codcurso));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2411,'$this->s_codcurso','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["s_codcurso"]))
         $resac = pg_query("insert into db_acount values($acount,391,2411,'$this->s_codcurso','".pg_result($resaco,0,'s_codcurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["s_nomecurso"]))
         $resac = pg_query("insert into db_acount values($acount,391,2412,'$this->s_nomecurso','".pg_result($resaco,0,'s_nomecurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["s_dtcurso"]))
         $resac = pg_query("insert into db_acount values($acount,391,2413,'$this->s_dtcurso','".pg_result($resaco,0,'s_dtcurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["s_vagcurso"]))
         $resac = pg_query("insert into db_acount values($acount,391,2414,'$this->s_vagcurso','".pg_result($resaco,0,'s_vagcurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["s_obscurso"]))
         $resac = pg_query("insert into db_acount values($acount,391,2415,'$this->s_obscurso','".pg_result($resaco,0,'s_obscurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["z01_numcgm"]))
         $resac = pg_query("insert into db_acount values($acount,391,216,'$this->z01_numcgm','".pg_result($resaco,0,'z01_numcgm')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["s_obscur"]))
         $resac = pg_query("insert into db_acount values($acount,391,2416,'$this->s_obscur','".pg_result($resaco,0,'s_obscur')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["s_sncurso"]))
         $resac = pg_query("insert into db_acount values($acount,391,2417,'$this->s_sncurso','".pg_result($resaco,0,'s_sncurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       pg_free_result($resaco);
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Curso de PHP nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s_codcurso;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Curso de PHP nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s_codcurso;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s_codcurso;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s_codcurso=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->s_codcurso));
     $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
     $acount = pg_result($resac,0,0);
     $resac = pg_query("insert into db_acountkey values($acount,2411,'".pg_result($resaco,$iresaco,'s_codcurso')."','E')");
     $resac = pg_query("insert into db_acount values($acount,391,2411,'','".pg_result($resaco,0,'s_codcurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,2412,'','".pg_result($resaco,0,'s_nomecurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,2413,'','".pg_result($resaco,0,'s_dtcurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,2414,'','".pg_result($resaco,0,'s_vagcurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,2415,'','".pg_result($resaco,0,'s_obscurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,216,'','".pg_result($resaco,0,'z01_numcgm')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,2416,'','".pg_result($resaco,0,'s_obscur')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,391,2417,'','".pg_result($resaco,0,'s_sncurso')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     pg_free_result($resaco);
     $sql = " delete from curso
                    where ";
     $sql2 = "";
      if($this->s_codcurso != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " s_codcurso = $this->s_codcurso ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Curso de PHP nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->s_codcurso;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Curso de PHP nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->s_codcurso;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s_codcurso;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s_codcurso=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from curso ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = curso.z01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($s_codcurso!=null ){
         $sql2 .= " where curso.s_codcurso = $s_codcurso "; 
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
   function sql_query_file ( $s_codcurso=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from curso ";
     $sql2 = "";
     if($dbwhere==""){
       if($s_codcurso!=null ){
         $sql2 .= " where curso.s_codcurso = $s_codcurso "; 
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