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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptucalc
class cl_iptucalc { 
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
   var $j23_anousu = 0; 
   var $j23_matric = 0; 
   var $j23_testad = 0; 
   var $j23_arealo = 0; 
   var $j23_areafr = 0; 
   var $j23_areaed = 0; 
   var $j23_m2terr = 0; 
   var $j23_vlrter = 0; 
   var $j23_aliq = 0; 
   var $j23_vlrisen = 0; 
   var $j23_tipoim = null; 
   var $j23_manual = null; 
   var $j23_tipocalculo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j23_anousu = int4 = Exercicio 
                 j23_matric = int4 = Matricula 
                 j23_testad = float8 = Testada do Calculo 
                 j23_arealo = float8 = Area Calculada 
                 j23_areafr = float8 = Area Fracionada 
                 j23_areaed = float8 = Area Total Edificada 
                 j23_m2terr = float8 = Valor M2 Terreno 
                 j23_vlrter = float8 = Valor Venal Terreno 
                 j23_aliq = float8 = Aliquita do Iptu 
                 j23_vlrisen = float8 = Valor da Isencao 
                 j23_tipoim = varchar(1) = Tipo de imposto 
                 j23_manual = text = Log do calculo 
                 j23_tipocalculo = int8 = Tipo de calculo 
                 ";
   //funcao construtor da classe 
   function cl_iptucalc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptucalc"); 
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
       $this->j23_anousu = ($this->j23_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_anousu"]:$this->j23_anousu);
       $this->j23_matric = ($this->j23_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_matric"]:$this->j23_matric);
       $this->j23_testad = ($this->j23_testad == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_testad"]:$this->j23_testad);
       $this->j23_arealo = ($this->j23_arealo == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_arealo"]:$this->j23_arealo);
       $this->j23_areafr = ($this->j23_areafr == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_areafr"]:$this->j23_areafr);
       $this->j23_areaed = ($this->j23_areaed == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_areaed"]:$this->j23_areaed);
       $this->j23_m2terr = ($this->j23_m2terr == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_m2terr"]:$this->j23_m2terr);
       $this->j23_vlrter = ($this->j23_vlrter == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_vlrter"]:$this->j23_vlrter);
       $this->j23_aliq = ($this->j23_aliq == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_aliq"]:$this->j23_aliq);
       $this->j23_vlrisen = ($this->j23_vlrisen == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_vlrisen"]:$this->j23_vlrisen);
       $this->j23_tipoim = ($this->j23_tipoim == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_tipoim"]:$this->j23_tipoim);
       $this->j23_manual = ($this->j23_manual == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_manual"]:$this->j23_manual);
       $this->j23_tipocalculo = ($this->j23_tipocalculo == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_tipocalculo"]:$this->j23_tipocalculo);
     }else{
       $this->j23_anousu = ($this->j23_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_anousu"]:$this->j23_anousu);
       $this->j23_matric = ($this->j23_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j23_matric"]:$this->j23_matric);
     }
   }
   // funcao para inclusao
   function incluir ($j23_anousu,$j23_matric){ 
      $this->atualizacampos();
     if($this->j23_testad == null ){ 
       $this->erro_sql = " Campo Testada do Calculo nao Informado.";
       $this->erro_campo = "j23_testad";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_arealo == null ){ 
       $this->erro_sql = " Campo Area Calculada nao Informado.";
       $this->erro_campo = "j23_arealo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_areafr == null ){ 
       $this->erro_sql = " Campo Area Fracionada nao Informado.";
       $this->erro_campo = "j23_areafr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_areaed == null ){ 
       $this->erro_sql = " Campo Area Total Edificada nao Informado.";
       $this->erro_campo = "j23_areaed";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_m2terr == null ){ 
       $this->erro_sql = " Campo Valor M2 Terreno nao Informado.";
       $this->erro_campo = "j23_m2terr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_vlrter == null ){ 
       $this->erro_sql = " Campo Valor Venal Terreno nao Informado.";
       $this->erro_campo = "j23_vlrter";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_aliq == null ){ 
       $this->erro_sql = " Campo Aliquita do Iptu nao Informado.";
       $this->erro_campo = "j23_aliq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_vlrisen == null ){ 
       $this->erro_sql = " Campo Valor da Isencao nao Informado.";
       $this->erro_campo = "j23_vlrisen";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_tipoim == null ){ 
       $this->erro_sql = " Campo Tipo de imposto nao Informado.";
       $this->erro_campo = "j23_tipoim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_manual == null ){ 
       $this->erro_sql = " Campo Log do calculo nao Informado.";
       $this->erro_campo = "j23_manual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j23_tipocalculo == null ){ 
       $this->erro_sql = " Campo Tipo de calculo nao Informado.";
       $this->erro_campo = "j23_tipocalculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j23_anousu = $j23_anousu; 
       $this->j23_matric = $j23_matric; 
     if(($this->j23_anousu == null) || ($this->j23_anousu == "") ){ 
       $this->erro_sql = " Campo j23_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j23_matric == null) || ($this->j23_matric == "") ){ 
       $this->erro_sql = " Campo j23_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptucalc(
                                       j23_anousu 
                                      ,j23_matric 
                                      ,j23_testad 
                                      ,j23_arealo 
                                      ,j23_areafr 
                                      ,j23_areaed 
                                      ,j23_m2terr 
                                      ,j23_vlrter 
                                      ,j23_aliq 
                                      ,j23_vlrisen 
                                      ,j23_tipoim 
                                      ,j23_manual 
                                      ,j23_tipocalculo 
                       )
                values (
                                $this->j23_anousu 
                               ,$this->j23_matric 
                               ,$this->j23_testad 
                               ,$this->j23_arealo 
                               ,$this->j23_areafr 
                               ,$this->j23_areaed 
                               ,$this->j23_m2terr 
                               ,$this->j23_vlrter 
                               ,$this->j23_aliq 
                               ,$this->j23_vlrisen 
                               ,'$this->j23_tipoim' 
                               ,'$this->j23_manual' 
                               ,$this->j23_tipocalculo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->j23_anousu."-".$this->j23_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->j23_anousu."-".$this->j23_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j23_anousu."-".$this->j23_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j23_anousu,$this->j23_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,591,'$this->j23_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,592,'$this->j23_matric','I')");
       $resac = db_query("insert into db_acount values($acount,115,591,'','".AddSlashes(pg_result($resaco,0,'j23_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,115,592,'','".AddSlashes(pg_result($resaco,0,'j23_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,115,593,'','".AddSlashes(pg_result($resaco,0,'j23_testad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,115,594,'','".AddSlashes(pg_result($resaco,0,'j23_arealo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,115,598,'','".AddSlashes(pg_result($resaco,0,'j23_areafr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,115,601,'','".AddSlashes(pg_result($resaco,0,'j23_areaed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,115,602,'','".AddSlashes(pg_result($resaco,0,'j23_m2terr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,115,605,'','".AddSlashes(pg_result($resaco,0,'j23_vlrter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,115,609,'','".AddSlashes(pg_result($resaco,0,'j23_aliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,115,618,'','".AddSlashes(pg_result($resaco,0,'j23_vlrisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,115,6834,'','".AddSlashes(pg_result($resaco,0,'j23_tipoim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,115,6835,'','".AddSlashes(pg_result($resaco,0,'j23_manual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,115,11024,'','".AddSlashes(pg_result($resaco,0,'j23_tipocalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j23_anousu=null,$j23_matric=null) { 
      $this->atualizacampos();
     $sql = " update iptucalc set ";
     $virgula = "";
     if(trim($this->j23_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_anousu"])){ 
       $sql  .= $virgula." j23_anousu = $this->j23_anousu ";
       $virgula = ",";
       if(trim($this->j23_anousu) == null ){ 
         $this->erro_sql = " Campo Exercicio nao Informado.";
         $this->erro_campo = "j23_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_matric"])){ 
       $sql  .= $virgula." j23_matric = $this->j23_matric ";
       $virgula = ",";
       if(trim($this->j23_matric) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "j23_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_testad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_testad"])){ 
       $sql  .= $virgula." j23_testad = $this->j23_testad ";
       $virgula = ",";
       if(trim($this->j23_testad) == null ){ 
         $this->erro_sql = " Campo Testada do Calculo nao Informado.";
         $this->erro_campo = "j23_testad";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_arealo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_arealo"])){ 
       $sql  .= $virgula." j23_arealo = $this->j23_arealo ";
       $virgula = ",";
       if(trim($this->j23_arealo) == null ){ 
         $this->erro_sql = " Campo Area Calculada nao Informado.";
         $this->erro_campo = "j23_arealo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_areafr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_areafr"])){ 
       $sql  .= $virgula." j23_areafr = $this->j23_areafr ";
       $virgula = ",";
       if(trim($this->j23_areafr) == null ){ 
         $this->erro_sql = " Campo Area Fracionada nao Informado.";
         $this->erro_campo = "j23_areafr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_areaed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_areaed"])){ 
       $sql  .= $virgula." j23_areaed = $this->j23_areaed ";
       $virgula = ",";
       if(trim($this->j23_areaed) == null ){ 
         $this->erro_sql = " Campo Area Total Edificada nao Informado.";
         $this->erro_campo = "j23_areaed";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_m2terr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_m2terr"])){ 
       $sql  .= $virgula." j23_m2terr = $this->j23_m2terr ";
       $virgula = ",";
       if(trim($this->j23_m2terr) == null ){ 
         $this->erro_sql = " Campo Valor M2 Terreno nao Informado.";
         $this->erro_campo = "j23_m2terr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_vlrter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_vlrter"])){ 
       $sql  .= $virgula." j23_vlrter = $this->j23_vlrter ";
       $virgula = ",";
       if(trim($this->j23_vlrter) == null ){ 
         $this->erro_sql = " Campo Valor Venal Terreno nao Informado.";
         $this->erro_campo = "j23_vlrter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_aliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_aliq"])){ 
       $sql  .= $virgula." j23_aliq = $this->j23_aliq ";
       $virgula = ",";
       if(trim($this->j23_aliq) == null ){ 
         $this->erro_sql = " Campo Aliquita do Iptu nao Informado.";
         $this->erro_campo = "j23_aliq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_vlrisen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_vlrisen"])){ 
       $sql  .= $virgula." j23_vlrisen = $this->j23_vlrisen ";
       $virgula = ",";
       if(trim($this->j23_vlrisen) == null ){ 
         $this->erro_sql = " Campo Valor da Isencao nao Informado.";
         $this->erro_campo = "j23_vlrisen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_tipoim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_tipoim"])){ 
       $sql  .= $virgula." j23_tipoim = '$this->j23_tipoim' ";
       $virgula = ",";
       if(trim($this->j23_tipoim) == null ){ 
         $this->erro_sql = " Campo Tipo de imposto nao Informado.";
         $this->erro_campo = "j23_tipoim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_manual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_manual"])){ 
       $sql  .= $virgula." j23_manual = '$this->j23_manual' ";
       $virgula = ",";
       if(trim($this->j23_manual) == null ){ 
         $this->erro_sql = " Campo Log do calculo nao Informado.";
         $this->erro_campo = "j23_manual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j23_tipocalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j23_tipocalculo"])){ 
       $sql  .= $virgula." j23_tipocalculo = $this->j23_tipocalculo ";
       $virgula = ",";
       if(trim($this->j23_tipocalculo) == null ){ 
         $this->erro_sql = " Campo Tipo de calculo nao Informado.";
         $this->erro_campo = "j23_tipocalculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j23_anousu!=null){
       $sql .= " j23_anousu = $this->j23_anousu";
     }
     if($j23_matric!=null){
       $sql .= " and  j23_matric = $this->j23_matric";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j23_anousu,$this->j23_matric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,591,'$this->j23_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,592,'$this->j23_matric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_anousu"]))
           $resac = db_query("insert into db_acount values($acount,115,591,'".AddSlashes(pg_result($resaco,$conresaco,'j23_anousu'))."','$this->j23_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_matric"]))
           $resac = db_query("insert into db_acount values($acount,115,592,'".AddSlashes(pg_result($resaco,$conresaco,'j23_matric'))."','$this->j23_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_testad"]))
           $resac = db_query("insert into db_acount values($acount,115,593,'".AddSlashes(pg_result($resaco,$conresaco,'j23_testad'))."','$this->j23_testad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_arealo"]))
           $resac = db_query("insert into db_acount values($acount,115,594,'".AddSlashes(pg_result($resaco,$conresaco,'j23_arealo'))."','$this->j23_arealo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_areafr"]))
           $resac = db_query("insert into db_acount values($acount,115,598,'".AddSlashes(pg_result($resaco,$conresaco,'j23_areafr'))."','$this->j23_areafr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_areaed"]))
           $resac = db_query("insert into db_acount values($acount,115,601,'".AddSlashes(pg_result($resaco,$conresaco,'j23_areaed'))."','$this->j23_areaed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_m2terr"]))
           $resac = db_query("insert into db_acount values($acount,115,602,'".AddSlashes(pg_result($resaco,$conresaco,'j23_m2terr'))."','$this->j23_m2terr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_vlrter"]))
           $resac = db_query("insert into db_acount values($acount,115,605,'".AddSlashes(pg_result($resaco,$conresaco,'j23_vlrter'))."','$this->j23_vlrter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_aliq"]))
           $resac = db_query("insert into db_acount values($acount,115,609,'".AddSlashes(pg_result($resaco,$conresaco,'j23_aliq'))."','$this->j23_aliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_vlrisen"]))
           $resac = db_query("insert into db_acount values($acount,115,618,'".AddSlashes(pg_result($resaco,$conresaco,'j23_vlrisen'))."','$this->j23_vlrisen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_tipoim"]))
           $resac = db_query("insert into db_acount values($acount,115,6834,'".AddSlashes(pg_result($resaco,$conresaco,'j23_tipoim'))."','$this->j23_tipoim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_manual"]))
           $resac = db_query("insert into db_acount values($acount,115,6835,'".AddSlashes(pg_result($resaco,$conresaco,'j23_manual'))."','$this->j23_manual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j23_tipocalculo"]))
           $resac = db_query("insert into db_acount values($acount,115,11024,'".AddSlashes(pg_result($resaco,$conresaco,'j23_tipocalculo'))."','$this->j23_tipocalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j23_anousu."-".$this->j23_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j23_anousu."-".$this->j23_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j23_anousu."-".$this->j23_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j23_anousu=null,$j23_matric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j23_anousu,$j23_matric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,591,'$j23_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,592,'$j23_matric','E')");
         $resac = db_query("insert into db_acount values($acount,115,591,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,115,592,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,115,593,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_testad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,115,594,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_arealo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,115,598,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_areafr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,115,601,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_areaed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,115,602,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_m2terr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,115,605,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_vlrter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,115,609,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_aliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,115,618,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_vlrisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,115,6834,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_tipoim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,115,6835,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_manual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,115,11024,'','".AddSlashes(pg_result($resaco,$iresaco,'j23_tipocalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptucalc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j23_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j23_anousu = $j23_anousu ";
        }
        if($j23_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j23_matric = $j23_matric ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j23_anousu."-".$j23_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j23_anousu."-".$j23_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j23_anousu."-".$j23_matric;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptucalc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j23_anousu=null,$j23_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalc ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptucalc.j23_matric";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($j23_anousu!=null ){
         $sql2 .= " where iptucalc.j23_anousu = $j23_anousu "; 
       } 
       if($j23_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptucalc.j23_matric = $j23_matric "; 
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
   function sql_query_file ( $j23_anousu=null,$j23_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalc ";
     $sql2 = "";
     if($dbwhere==""){
       if($j23_anousu!=null ){
         $sql2 .= " where iptucalc.j23_anousu = $j23_anousu "; 
       } 
       if($j23_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptucalc.j23_matric = $j23_matric "; 
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