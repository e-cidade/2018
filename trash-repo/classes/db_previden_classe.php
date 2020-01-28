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
//CLASSE DA ENTIDADE previden
class cl_previden { 
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
   var $r60_anousu = 0; 
   var $r60_mesusu = 0; 
   var $r60_numcgm = 0; 
   var $r60_tbprev = 0; 
   var $r60_rubric = null; 
   var $r60_regist = 0; 
   var $r60_folha = null; 
   var $r60_base = 0; 
   var $r60_dprev = 0; 
   var $r60_pdesc = 0; 
   var $r60_novod = 0; 
   var $r60_novop = 0; 
   var $r60_altera = 'f'; 
   var $r60_ajuste = 'f'; 
   var $r60_basef = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r60_anousu = int4 = Ano do Exercicio 
                 r60_mesusu = int4 = Mes do Exercicio 
                 r60_numcgm = int4 = Numero CGM 
                 r60_tbprev = int4 = Tabela para calculo da Prev. 
                 r60_rubric = char(     4) = Codigo da Rubrica da base 
                 r60_regist = int4 = Codigo do Funcionario 
                 r60_folha = char(     1) = Codigo do Tipo de Folha 
                 r60_base = float8 = valor da Base 
                 r60_dprev = float8 = desc. previdencia original 
                 r60_pdesc = float8 = perc.original da previdencia 
                 r60_novod = float8 = novo valor desconto previdenci 
                 r60_novop = float8 = novo percent.desconto de prev. 
                 r60_altera = boolean = informa de deve alterar descon 
                 r60_ajuste = boolean = informa se e do mesmo numcgm 
                 r60_basef = float8 = Valor da R992 
                 ";
   //funcao construtor da classe 
   function cl_previden() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("previden"); 
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
       $this->r60_anousu = ($this->r60_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_anousu"]:$this->r60_anousu);
       $this->r60_mesusu = ($this->r60_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_mesusu"]:$this->r60_mesusu);
       $this->r60_numcgm = ($this->r60_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_numcgm"]:$this->r60_numcgm);
       $this->r60_tbprev = ($this->r60_tbprev == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_tbprev"]:$this->r60_tbprev);
       $this->r60_rubric = ($this->r60_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_rubric"]:$this->r60_rubric);
       $this->r60_regist = ($this->r60_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_regist"]:$this->r60_regist);
       $this->r60_folha = ($this->r60_folha == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_folha"]:$this->r60_folha);
       $this->r60_base = ($this->r60_base == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_base"]:$this->r60_base);
       $this->r60_dprev = ($this->r60_dprev == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_dprev"]:$this->r60_dprev);
       $this->r60_pdesc = ($this->r60_pdesc == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_pdesc"]:$this->r60_pdesc);
       $this->r60_novod = ($this->r60_novod == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_novod"]:$this->r60_novod);
       $this->r60_novop = ($this->r60_novop == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_novop"]:$this->r60_novop);
       $this->r60_altera = ($this->r60_altera == "f"?@$GLOBALS["HTTP_POST_VARS"]["r60_altera"]:$this->r60_altera);
       $this->r60_ajuste = ($this->r60_ajuste == "f"?@$GLOBALS["HTTP_POST_VARS"]["r60_ajuste"]:$this->r60_ajuste);
       $this->r60_basef = ($this->r60_basef == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_basef"]:$this->r60_basef);
     }else{
       $this->r60_anousu = ($this->r60_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_anousu"]:$this->r60_anousu);
       $this->r60_mesusu = ($this->r60_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_mesusu"]:$this->r60_mesusu);
       $this->r60_numcgm = ($this->r60_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_numcgm"]:$this->r60_numcgm);
       $this->r60_tbprev = ($this->r60_tbprev == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_tbprev"]:$this->r60_tbprev);
       $this->r60_rubric = ($this->r60_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r60_rubric"]:$this->r60_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r60_anousu,$r60_mesusu,$r60_numcgm,$r60_tbprev,$r60_rubric){ 
      $this->atualizacampos();
     if($this->r60_regist == null ){ 
       $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
       $this->erro_campo = "r60_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r60_folha == null ){ 
       $this->erro_sql = " Campo Codigo do Tipo de Folha nao Informado.";
       $this->erro_campo = "r60_folha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r60_base == null ){ 
       $this->erro_sql = " Campo valor da Base nao Informado.";
       $this->erro_campo = "r60_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r60_dprev == null ){ 
       $this->erro_sql = " Campo desc. previdencia original nao Informado.";
       $this->erro_campo = "r60_dprev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r60_pdesc == null ){ 
       $this->erro_sql = " Campo perc.original da previdencia nao Informado.";
       $this->erro_campo = "r60_pdesc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r60_novod == null ){ 
       $this->erro_sql = " Campo novo valor desconto previdenci nao Informado.";
       $this->erro_campo = "r60_novod";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r60_novop == null ){ 
       $this->erro_sql = " Campo novo percent.desconto de prev. nao Informado.";
       $this->erro_campo = "r60_novop";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r60_altera == null ){ 
       $this->erro_sql = " Campo informa de deve alterar descon nao Informado.";
       $this->erro_campo = "r60_altera";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r60_ajuste == null ){ 
       $this->erro_sql = " Campo informa se e do mesmo numcgm nao Informado.";
       $this->erro_campo = "r60_ajuste";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r60_basef == null ){ 
       $this->erro_sql = " Campo Valor da R992 nao Informado.";
       $this->erro_campo = "r60_basef";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r60_anousu = $r60_anousu; 
       $this->r60_mesusu = $r60_mesusu; 
       $this->r60_numcgm = $r60_numcgm; 
       $this->r60_tbprev = $r60_tbprev; 
       $this->r60_rubric = $r60_rubric; 
     if(($this->r60_anousu == null) || ($this->r60_anousu == "") ){ 
       $this->erro_sql = " Campo r60_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r60_mesusu == null) || ($this->r60_mesusu == "") ){ 
       $this->erro_sql = " Campo r60_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r60_numcgm == null) || ($this->r60_numcgm == "") ){ 
       $this->erro_sql = " Campo r60_numcgm nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r60_tbprev == null) || ($this->r60_tbprev == "") ){ 
       $this->erro_sql = " Campo r60_tbprev nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r60_rubric == null) || ($this->r60_rubric == "") ){ 
       $this->erro_sql = " Campo r60_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into previden(
                                       r60_anousu 
                                      ,r60_mesusu 
                                      ,r60_numcgm 
                                      ,r60_tbprev 
                                      ,r60_rubric 
                                      ,r60_regist 
                                      ,r60_folha 
                                      ,r60_base 
                                      ,r60_dprev 
                                      ,r60_pdesc 
                                      ,r60_novod 
                                      ,r60_novop 
                                      ,r60_altera 
                                      ,r60_ajuste 
                                      ,r60_basef 
                       )
                values (
                                $this->r60_anousu 
                               ,$this->r60_mesusu 
                               ,$this->r60_numcgm 
                               ,$this->r60_tbprev 
                               ,'$this->r60_rubric' 
                               ,$this->r60_regist 
                               ,'$this->r60_folha' 
                               ,$this->r60_base 
                               ,$this->r60_dprev 
                               ,$this->r60_pdesc 
                               ,$this->r60_novod 
                               ,$this->r60_novop 
                               ,'$this->r60_altera' 
                               ,'$this->r60_ajuste' 
                               ,$this->r60_basef 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ajuste de Previdencias ($this->r60_anousu."-".$this->r60_mesusu."-".$this->r60_numcgm."-".$this->r60_tbprev."-".$this->r60_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ajuste de Previdencias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ajuste de Previdencias ($this->r60_anousu."-".$this->r60_mesusu."-".$this->r60_numcgm."-".$this->r60_tbprev."-".$this->r60_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r60_anousu."-".$this->r60_mesusu."-".$this->r60_numcgm."-".$this->r60_tbprev."-".$this->r60_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r60_anousu,$this->r60_mesusu,$this->r60_numcgm,$this->r60_tbprev,$this->r60_rubric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4331,'$this->r60_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4332,'$this->r60_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4333,'$this->r60_numcgm','I')");
       $resac = db_query("insert into db_acountkey values($acount,4334,'$this->r60_tbprev','I')");
       $resac = db_query("insert into db_acountkey values($acount,4335,'$this->r60_rubric','I')");
       $resac = db_query("insert into db_acount values($acount,581,4331,'','".AddSlashes(pg_result($resaco,0,'r60_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4332,'','".AddSlashes(pg_result($resaco,0,'r60_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4333,'','".AddSlashes(pg_result($resaco,0,'r60_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4334,'','".AddSlashes(pg_result($resaco,0,'r60_tbprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4335,'','".AddSlashes(pg_result($resaco,0,'r60_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4336,'','".AddSlashes(pg_result($resaco,0,'r60_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4337,'','".AddSlashes(pg_result($resaco,0,'r60_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4338,'','".AddSlashes(pg_result($resaco,0,'r60_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4339,'','".AddSlashes(pg_result($resaco,0,'r60_dprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4340,'','".AddSlashes(pg_result($resaco,0,'r60_pdesc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4341,'','".AddSlashes(pg_result($resaco,0,'r60_novod'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4342,'','".AddSlashes(pg_result($resaco,0,'r60_novop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4343,'','".AddSlashes(pg_result($resaco,0,'r60_altera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,4344,'','".AddSlashes(pg_result($resaco,0,'r60_ajuste'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,581,14158,'','".AddSlashes(pg_result($resaco,0,'r60_basef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r60_anousu=null,$r60_mesusu=null,$r60_numcgm=null,$r60_tbprev=null,$r60_rubric=null) { 
      $this->atualizacampos();
     $sql = " update previden set ";
     $virgula = "";
     if(trim($this->r60_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_anousu"])){ 
       $sql  .= $virgula." r60_anousu = $this->r60_anousu ";
       $virgula = ",";
       if(trim($this->r60_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r60_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_mesusu"])){ 
       $sql  .= $virgula." r60_mesusu = $this->r60_mesusu ";
       $virgula = ",";
       if(trim($this->r60_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r60_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_numcgm"])){ 
       $sql  .= $virgula." r60_numcgm = $this->r60_numcgm ";
       $virgula = ",";
       if(trim($this->r60_numcgm) == null ){ 
         $this->erro_sql = " Campo Numero CGM nao Informado.";
         $this->erro_campo = "r60_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_tbprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_tbprev"])){ 
       $sql  .= $virgula." r60_tbprev = $this->r60_tbprev ";
       $virgula = ",";
       if(trim($this->r60_tbprev) == null ){ 
         $this->erro_sql = " Campo Tabela para calculo da Prev. nao Informado.";
         $this->erro_campo = "r60_tbprev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_rubric"])){ 
       $sql  .= $virgula." r60_rubric = '$this->r60_rubric' ";
       $virgula = ",";
       if(trim($this->r60_rubric) == null ){ 
         $this->erro_sql = " Campo Codigo da Rubrica da base nao Informado.";
         $this->erro_campo = "r60_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_regist"])){ 
       $sql  .= $virgula." r60_regist = $this->r60_regist ";
       $virgula = ",";
       if(trim($this->r60_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r60_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_folha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_folha"])){ 
       $sql  .= $virgula." r60_folha = '$this->r60_folha' ";
       $virgula = ",";
       if(trim($this->r60_folha) == null ){ 
         $this->erro_sql = " Campo Codigo do Tipo de Folha nao Informado.";
         $this->erro_campo = "r60_folha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_base"])){ 
       $sql  .= $virgula." r60_base = $this->r60_base ";
       $virgula = ",";
       if(trim($this->r60_base) == null ){ 
         $this->erro_sql = " Campo valor da Base nao Informado.";
         $this->erro_campo = "r60_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_dprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_dprev"])){ 
       $sql  .= $virgula." r60_dprev = $this->r60_dprev ";
       $virgula = ",";
       if(trim($this->r60_dprev) == null ){ 
         $this->erro_sql = " Campo desc. previdencia original nao Informado.";
         $this->erro_campo = "r60_dprev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_pdesc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_pdesc"])){ 
       $sql  .= $virgula." r60_pdesc = $this->r60_pdesc ";
       $virgula = ",";
       if(trim($this->r60_pdesc) == null ){ 
         $this->erro_sql = " Campo perc.original da previdencia nao Informado.";
         $this->erro_campo = "r60_pdesc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_novod)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_novod"])){ 
       $sql  .= $virgula." r60_novod = $this->r60_novod ";
       $virgula = ",";
       if(trim($this->r60_novod) == null ){ 
         $this->erro_sql = " Campo novo valor desconto previdenci nao Informado.";
         $this->erro_campo = "r60_novod";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_novop)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_novop"])){ 
       $sql  .= $virgula." r60_novop = $this->r60_novop ";
       $virgula = ",";
       if(trim($this->r60_novop) == null ){ 
         $this->erro_sql = " Campo novo percent.desconto de prev. nao Informado.";
         $this->erro_campo = "r60_novop";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_altera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_altera"])){ 
       $sql  .= $virgula." r60_altera = '$this->r60_altera' ";
       $virgula = ",";
       if(trim($this->r60_altera) == null ){ 
         $this->erro_sql = " Campo informa de deve alterar descon nao Informado.";
         $this->erro_campo = "r60_altera";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_ajuste)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_ajuste"])){ 
       $sql  .= $virgula." r60_ajuste = '$this->r60_ajuste' ";
       $virgula = ",";
       if(trim($this->r60_ajuste) == null ){ 
         $this->erro_sql = " Campo informa se e do mesmo numcgm nao Informado.";
         $this->erro_campo = "r60_ajuste";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r60_basef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r60_basef"])){ 
       $sql  .= $virgula." r60_basef = $this->r60_basef ";
       $virgula = ",";
       if(trim($this->r60_basef) == null ){ 
         $this->erro_sql = " Campo Valor da R992 nao Informado.";
         $this->erro_campo = "r60_basef";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r60_anousu!=null){
       $sql .= " r60_anousu = $this->r60_anousu";
     }
     if($r60_mesusu!=null){
       $sql .= " and  r60_mesusu = $this->r60_mesusu";
     }
     if($r60_numcgm!=null){
       $sql .= " and  r60_numcgm = $this->r60_numcgm";
     }
     if($r60_tbprev!=null){
       $sql .= " and  r60_tbprev = $this->r60_tbprev";
     }
     if($r60_rubric!=null){
       $sql .= " and  r60_rubric = '$this->r60_rubric'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r60_anousu,$this->r60_mesusu,$this->r60_numcgm,$this->r60_tbprev,$this->r60_rubric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4331,'$this->r60_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4332,'$this->r60_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4333,'$this->r60_numcgm','A')");
         $resac = db_query("insert into db_acountkey values($acount,4334,'$this->r60_tbprev','A')");
         $resac = db_query("insert into db_acountkey values($acount,4335,'$this->r60_rubric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_anousu"]) || $this->r60_anousu != "")
           $resac = db_query("insert into db_acount values($acount,581,4331,'".AddSlashes(pg_result($resaco,$conresaco,'r60_anousu'))."','$this->r60_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_mesusu"]) || $this->r60_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,581,4332,'".AddSlashes(pg_result($resaco,$conresaco,'r60_mesusu'))."','$this->r60_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_numcgm"]) || $this->r60_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,581,4333,'".AddSlashes(pg_result($resaco,$conresaco,'r60_numcgm'))."','$this->r60_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_tbprev"]) || $this->r60_tbprev != "")
           $resac = db_query("insert into db_acount values($acount,581,4334,'".AddSlashes(pg_result($resaco,$conresaco,'r60_tbprev'))."','$this->r60_tbprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_rubric"]) || $this->r60_rubric != "")
           $resac = db_query("insert into db_acount values($acount,581,4335,'".AddSlashes(pg_result($resaco,$conresaco,'r60_rubric'))."','$this->r60_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_regist"]) || $this->r60_regist != "")
           $resac = db_query("insert into db_acount values($acount,581,4336,'".AddSlashes(pg_result($resaco,$conresaco,'r60_regist'))."','$this->r60_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_folha"]) || $this->r60_folha != "")
           $resac = db_query("insert into db_acount values($acount,581,4337,'".AddSlashes(pg_result($resaco,$conresaco,'r60_folha'))."','$this->r60_folha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_base"]) || $this->r60_base != "")
           $resac = db_query("insert into db_acount values($acount,581,4338,'".AddSlashes(pg_result($resaco,$conresaco,'r60_base'))."','$this->r60_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_dprev"]) || $this->r60_dprev != "")
           $resac = db_query("insert into db_acount values($acount,581,4339,'".AddSlashes(pg_result($resaco,$conresaco,'r60_dprev'))."','$this->r60_dprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_pdesc"]) || $this->r60_pdesc != "")
           $resac = db_query("insert into db_acount values($acount,581,4340,'".AddSlashes(pg_result($resaco,$conresaco,'r60_pdesc'))."','$this->r60_pdesc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_novod"]) || $this->r60_novod != "")
           $resac = db_query("insert into db_acount values($acount,581,4341,'".AddSlashes(pg_result($resaco,$conresaco,'r60_novod'))."','$this->r60_novod',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_novop"]) || $this->r60_novop != "")
           $resac = db_query("insert into db_acount values($acount,581,4342,'".AddSlashes(pg_result($resaco,$conresaco,'r60_novop'))."','$this->r60_novop',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_altera"]) || $this->r60_altera != "")
           $resac = db_query("insert into db_acount values($acount,581,4343,'".AddSlashes(pg_result($resaco,$conresaco,'r60_altera'))."','$this->r60_altera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_ajuste"]) || $this->r60_ajuste != "")
           $resac = db_query("insert into db_acount values($acount,581,4344,'".AddSlashes(pg_result($resaco,$conresaco,'r60_ajuste'))."','$this->r60_ajuste',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r60_basef"]) || $this->r60_basef != "")
           $resac = db_query("insert into db_acount values($acount,581,14158,'".AddSlashes(pg_result($resaco,$conresaco,'r60_basef'))."','$this->r60_basef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ajuste de Previdencias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r60_anousu."-".$this->r60_mesusu."-".$this->r60_numcgm."-".$this->r60_tbprev."-".$this->r60_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ajuste de Previdencias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r60_anousu."-".$this->r60_mesusu."-".$this->r60_numcgm."-".$this->r60_tbprev."-".$this->r60_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r60_anousu."-".$this->r60_mesusu."-".$this->r60_numcgm."-".$this->r60_tbprev."-".$this->r60_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r60_anousu=null,$r60_mesusu=null,$r60_numcgm=null,$r60_tbprev=null,$r60_rubric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r60_anousu,$r60_mesusu,$r60_numcgm,$r60_tbprev,$r60_rubric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4331,'$r60_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4332,'$r60_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4333,'$r60_numcgm','E')");
         $resac = db_query("insert into db_acountkey values($acount,4334,'$r60_tbprev','E')");
         $resac = db_query("insert into db_acountkey values($acount,4335,'$r60_rubric','E')");
         $resac = db_query("insert into db_acount values($acount,581,4331,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4332,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4333,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4334,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_tbprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4335,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4336,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4337,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4338,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4339,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_dprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4340,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_pdesc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4341,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_novod'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4342,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_novop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4343,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_altera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,4344,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_ajuste'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,581,14158,'','".AddSlashes(pg_result($resaco,$iresaco,'r60_basef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from previden
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r60_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r60_anousu = $r60_anousu ";
        }
        if($r60_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r60_mesusu = $r60_mesusu ";
        }
        if($r60_numcgm != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r60_numcgm = $r60_numcgm ";
        }
        if($r60_tbprev != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r60_tbprev = $r60_tbprev ";
        }
        if($r60_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r60_rubric = '$r60_rubric' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ajuste de Previdencias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r60_anousu."-".$r60_mesusu."-".$r60_numcgm."-".$r60_tbprev."-".$r60_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ajuste de Previdencias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r60_anousu."-".$r60_mesusu."-".$r60_numcgm."-".$r60_tbprev."-".$r60_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r60_anousu."-".$r60_mesusu."-".$r60_numcgm."-".$r60_tbprev."-".$r60_rubric;
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
        $this->erro_sql   = "Record Vazio na Tabela:previden";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r60_anousu=null,$r60_mesusu=null,$r60_numcgm=null,$r60_tbprev=null,$r60_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from previden ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = previden.r60_numcgm";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = previden.r60_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql2 = "";
     if($dbwhere==""){
       if($r60_anousu!=null ){
         $sql2 .= " where previden.r60_anousu = $r60_anousu "; 
       } 
       if($r60_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " previden.r60_mesusu = $r60_mesusu "; 
       } 
       if($r60_numcgm!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " previden.r60_numcgm = $r60_numcgm "; 
       } 
       if($r60_tbprev!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " previden.r60_tbprev = $r60_tbprev "; 
       } 
       if($r60_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " previden.r60_rubric = '$r60_rubric' "; 
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
   function sql_query_file ( $r60_anousu=null,$r60_mesusu=null,$r60_numcgm=null,$r60_tbprev=null,$r60_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from previden ";
     $sql2 = "";
     if($dbwhere==""){
       if($r60_anousu!=null ){
         $sql2 .= " where previden.r60_anousu = $r60_anousu "; 
       } 
       if($r60_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " previden.r60_mesusu = $r60_mesusu "; 
       } 
       if($r60_numcgm!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " previden.r60_numcgm = $r60_numcgm "; 
       } 
       if($r60_tbprev!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " previden.r60_tbprev = $r60_tbprev "; 
       } 
       if($r60_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " previden.r60_rubric = '$r60_rubric' "; 
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